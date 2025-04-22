<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RenewSubscriptions extends Command
{
    protected $signature = 'subscriptions:renew';
    protected $description = 'Renew subscriptions with auto renewal enabled when remaining visits reach 0';

    public function handle()
    {
        $subscriptions = Subscription::with(['bookings', 'package', 'invoices'])
            ->where('remaining_visits', 0)
            ->where('auto_renewal', true)
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->renewSubscription($subscription);
        }

        $this->info('Auto-renewal check completed. Renewed: ' . $subscriptions->count() . ' subscriptions');
    }

    protected function renewSubscription($subscription)
    {
        try {
            DB::beginTransaction();

            // 1. البحث عن فاتورة موجودة أو إنشاء فاتورة جديدة
            $invoice = $subscription->invoices()->first();

            if (!$invoice) {
                $invoice = Invoice::create([
                    'customer_id' => $subscription->customer_id,
                    'subscription_id' => $subscription->id,
                    'total_amount' => $subscription->package->price,
                    'amount_paid' => 0,
                    'remaining_amount' => $subscription->package->price,
                ]);
            }

            // 2. تجديد عدد الزيارات
            $subscription->update([
                'remaining_visits' => $subscription->package->number,
                'used_visits' => 0
            ]);

            // 3. إنشاء معاملة تجديد
            Transaction::create([
                'invoice_id' => $invoice->id,
                'type' => 'debit',
                'amount' => $subscription->package->price,
                'notes' => 'تجديد تلقائي للاشتراك - ' . $subscription->package->type,
            ]);

            // 4. إنشاء الحجوزات الجديدة
            $this->recreateBookings($subscription);

            DB::commit();

            Log::info('Subscription renewed successfully', [
                'subscription_id' => $subscription->id,
                'customer_id' => $subscription->customer_id,
                'new_visits' => $subscription->package->number,
                'invoice_id' => $invoice->id
            ]);

            $this->info('Subscription ID ' . $subscription->id . ' renewed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription renewal failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
            $this->error('Error renewing subscription ' . $subscription->id . ': ' . $e->getMessage());
        }
    }

    protected function recreateBookings($subscription)
    {
        $originalBookings = $subscription->bookings()
            ->orderBy('date_of_day')
            ->orderBy('start_time')
            ->get();

        if ($originalBookings->isEmpty()) {
            return;
        }

        $packageType = $subscription->package->type;
        $interval = $this->getInterval($packageType);

        foreach ($originalBookings as $booking) {
            $newDate = $this->calculateNewDate($booking->date_of_day, $interval);

            Booking::create([
                'subscription_id' => $subscription->id,
                'customer_id' => $subscription->customer_id,
                'receptionist_id' => $booking->receptionist_id,
                'date_of_day' => $newDate,
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'type' => $booking->type,
                'notes' => 'تجديد تلقائي - ' . ($booking->notes ?? ''),
                'location' => $booking->location,
            ]);
        }
    }

    protected function getInterval($packageType)
    {
        switch ($packageType) {
            case 'daily': return 1;
            case 'weekly': return 7;
            case 'monthly': return 30;
            case '3month': return 90;
            case '6month': return 180;
            case '9month': return 270;
            case 'yearly': return 365;
            default: return 7;
        }
    }

    protected function calculateNewDate($originalDate, $daysToAdd)
    {
        return Carbon::parse($originalDate)->addDays($daysToAdd)->format('Y-m-d');
    }
}
