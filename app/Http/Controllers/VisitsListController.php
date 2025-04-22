<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Cleaner;
use App\Models\Package;
use App\Models\Project;
use App\Models\Service;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class VisitsListController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:visits-list', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $customers = Customer::all();
        $packages = Package::all();
        $today = now()->format('Y-m-d');
        $cleaners = Cleaner::where('status', 'available')->get();

        // Apply filters if they exist
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $customerId = $request->input('customer_id');
        $cleanerId = $request->input('cleaner_id');
        $status = $request->input('status');

        // Query for booked days with filters
        $bookedDaysQuery = Booking::selectRaw('DATE(date_of_day) as day, COUNT(*) as booking_count');

        if ($dateFrom) {
            $bookedDaysQuery->whereDate('date_of_day', '>=', $dateFrom);
        }

        if ($dateTo) {
            $bookedDaysQuery->whereDate('date_of_day', '<=', $dateTo);
        }

        if ($customerId) {
            $bookedDaysQuery->where('customer_id', $customerId);
        }

        if ($cleanerId) {
            $bookedDaysQuery->where('cleaner_id', $cleanerId);
        }

        if ($status) {
            switch ($status) {
                case 'pending':
                    $bookedDaysQuery->whereNull('execution_date');
                    break;
                case 'executed':
                    $bookedDaysQuery->whereNotNull('execution_date');
                    break;
                case 'returned':
                    $bookedDaysQuery->whereNotNull('return_date');
                    break;
            }
        }

        // Get paginated booked days
        $bookedDays = $bookedDaysQuery->groupBy('day')
            ->paginate(10);

        // Query for expired customers with filters
        $expiredCustomersQuery = Customer::whereHas('subscriptions', function ($query) {
            $query->where('remaining_visits', 0);
        });

        if ($customerId) {
            $expiredCustomersQuery->where('id', $customerId);
        }

        // Get paginated expired customers
        $expiredCustomers = $expiredCustomersQuery->paginate(10);

        // Query for pending bookings with filters
        $pendingBookingsQuery = Booking::whereNull('execution_date');

        if ($dateFrom || $dateTo) {
            $pendingBookingsQuery->whereDate('date_of_day', Carbon::today());
        } else {
            $pendingBookingsQuery->whereDate('date_of_day', Carbon::today());
        }

        if ($customerId) {
            $pendingBookingsQuery->where('customer_id', $customerId);
        }

        if ($cleanerId) {
            $pendingBookingsQuery->where('cleaner_id', $cleanerId);
        }

        // Get paginated pending bookings
        $pendingBookings = $pendingBookingsQuery->get();

        // Query for executed bookings with filters
        $executedBookingsQuery = Booking::whereNotNull('execution_date')
            ->whereNull('return_date');

        if ($customerId) {
            $executedBookingsQuery->where('customer_id', $customerId);
        }

        if ($cleanerId) {
            $executedBookingsQuery->where('cleaner_id', $cleanerId);
        }

        // Get paginated executed bookings
        $executedBookings = $executedBookingsQuery->get();

        // Today's bookings with filters
        $todayBookingsQuery = Booking::whereDate('date_of_day', $today);

        if ($customerId) {
            $todayBookingsQuery->where('customer_id', $customerId);
        }

        if ($cleanerId) {
            $todayBookingsQuery->where('cleaner_id', $cleanerId);
        }

        if ($status) {
            switch ($status) {
                case 'pending':
                    $todayBookingsQuery->whereNull('execution_date');
                    break;
                case 'executed':
                    $todayBookingsQuery->whereNotNull('execution_date');
                    break;
                case 'returned':
                    $todayBookingsQuery->whereNotNull('return_date');
                    break;
            }
        }

        $todayBookings = $todayBookingsQuery->get();

        return view('admin.index', compact(
            'customers',
            'packages',
            'bookedDays',
            'pendingBookings',
            'executedBookings',
            'today',
            'todayBookings',
            'expiredCustomers',
            'cleaners',
            'dateFrom',
            'dateTo',
            'customerId',
            'cleanerId',
            'status'
        ));
    }

    // Add new method to handle AJAX filter requests
    public function filterDashboardData(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $customerId = $request->input('customer_id');
        $cleanerId = $request->input('cleaner_id');
        $status = $request->input('status');

        // Query for booked days with filters
        $bookedDaysQuery = Booking::selectRaw('DATE(date_of_day) as day, COUNT(*) as booking_count');

        if ($dateFrom) {
            $bookedDaysQuery->whereDate('date_of_day', '>=', $dateFrom);
        }

        if ($dateTo) {
            $bookedDaysQuery->whereDate('date_of_day', '<=', $dateTo);
        }

        if ($customerId) {
            $bookedDaysQuery->where('customer_id', $customerId);
        }

        if ($cleanerId) {
            $bookedDaysQuery->where('cleaner_id', $cleanerId);
        }

        if ($status) {
            switch ($status) {
                case 'pending':
                    $bookedDaysQuery->whereNull('execution_date');
                    break;
                case 'executed':
                    $bookedDaysQuery->whereNotNull('execution_date');
                    break;
                case 'returned':
                    $bookedDaysQuery->whereNotNull('return_date');
                    break;
            }
        }

        $bookedDays = $bookedDaysQuery->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'bookedDays' => $bookedDays,
            'pagination' => $bookedDays->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    public function getBookingsByDay(Request $request)
    {
        try {
            $day = $request->input('day');
            if (!$day) {
                return response()->json([
                    'success' => false,
                    'message' => 'Se requiere un día válido'
                ], 400);
            }

            $morningFrom = '00:00:00';
            $morningTo = '12:00:00';
            $eveningFrom = '12:00:01';
            $eveningTo = '23:59:59';

            // Apply filters if they exist
            $customerId = $request->input('customer_id');
            $cleanerId = $request->input('cleaner_id');
            $location = $request->input('location');
            $status = $request->input('status');
            $timeFrom = $request->input('time_from');
            $timeTo = $request->input('time_to');

            // Morning visits query
            $morningVisitsQuery = Booking::with(['customer', 'cleaner', 'receptionist', 'subscription.package'])
                ->whereDate('date_of_day', $day)
                ->whereTime('start_time', '>=', $morningFrom)
                ->whereTime('start_time', '<=', $morningTo);

            // Evening visits query
            $eveningVisitsQuery = Booking::with(['customer', 'cleaner', 'receptionist', 'subscription.package'])
                ->whereDate('date_of_day', $day)
                ->whereTime('start_time', '>=', $eveningFrom)
                ->whereTime('start_time', '<=', $eveningTo);

            // Apply additional filters if provided
            if ($customerId) {
                $morningVisitsQuery->where('customer_id', $customerId);
                $eveningVisitsQuery->where('customer_id', $customerId);
            }

            if ($cleanerId) {
                $morningVisitsQuery->where('cleaner_id', $cleanerId);
                $eveningVisitsQuery->where('cleaner_id', $cleanerId);
            }

            if ($location) {
                $morningVisitsQuery->where('location', 'like', '%' . $location . '%');
                $eveningVisitsQuery->where('location', 'like', '%' . $location . '%');
            }

            if ($status) {
                switch ($status) {
                    case 'pending':
                        $morningVisitsQuery->whereNull('execution_date');
                        $eveningVisitsQuery->whereNull('execution_date');
                        break;
                    case 'executed':
                        $morningVisitsQuery->whereNotNull('execution_date');
                        $eveningVisitsQuery->whereNotNull('execution_date');
                        break;
                    case 'canceled':
                        $morningVisitsQuery->where('status', 'canceled');
                        $eveningVisitsQuery->where('status', 'canceled');
                        break;
                }
            }

            if ($timeFrom) {
                $morningVisitsQuery->whereTime('start_time', '>=', $timeFrom);
                $eveningVisitsQuery->whereTime('start_time', '>=', $timeFrom);
            }

            if ($timeTo) {
                $morningVisitsQuery->whereTime('start_time', '<=', $timeTo);
                $eveningVisitsQuery->whereTime('start_time', '<=', $timeTo);
            }

            $morningVisits = $morningVisitsQuery->get();
            $eveningVisits = $eveningVisitsQuery->get();

            // Check if we are requesting only morning or evening
            if ($request->input('period') === 'morning') {
                return response()->json([
                    'success' => true,
                    'visits' => $morningVisits,
                    'visitsHtml' => $this->renderVisitsHtml($morningVisits)
                ]);
            } elseif ($request->input('period') === 'evening') {
                return response()->json([
                    'success' => true,
                    'visits' => $eveningVisits,
                    'visitsHtml' => $this->renderVisitsHtml($eveningVisits)
                ]);
            }

            // Render visits view
            $morningVisitsHtml = $this->renderVisitsHtml($morningVisits);
            $eveningVisitsHtml = $this->renderVisitsHtml($eveningVisits);

            return response()->json([
                'success' => true,
                'morningVisits' => $morningVisitsHtml,
                'eveningVisits' => $eveningVisitsHtml
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bookings by specific date and status
     */
    public function getBookingsByDate(Request $request)
    {
        try {
            $date = $request->input('date');
            $status = $request->input('status', 'pending'); // Default to pending if not specified

            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى تحديد تاريخ صالح'
                ], 400);
            }

            // Query bookings for the selected date with the selected status
            $bookingsQuery = Booking::with('customer')
                ->whereDate('date_of_day', $date);

            // Filter by status
            switch ($status) {
                case 'pending':
                    $bookingsQuery->whereNull('execution_date');
                    break;
                case 'executed':
                    $bookingsQuery->whereNotNull('execution_date')
                                 ->whereNull('return_date');
                    break;
                case 'returned':
                    $bookingsQuery->whereNotNull('return_date');
                    break;
            }

            $bookings = $bookingsQuery->get();

            // Format bookings for the response
            $formattedBookings = $bookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    'customer_name' => $booking->customer->name,
                    'date_of_day' => $booking->date_of_day,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'location' => $booking->location,
                    'status' => $booking->execution_date ? 'executed' : 'pending',
                ];
            });

            return response()->json([
                'success' => true,
                'bookings' => $formattedBookings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function renderVisitsHtml($visits)
    {
        $html = '';
        if ($visits->count() > 0) {
            foreach ($visits as $index => $visit) {
                $rowClass = '';
                if (is_null($visit->execution_date) && is_null($visit->return_date)) {
                    $rowClass = 'table-danger';
                } elseif (is_null($visit->return_date)) {
                    $rowClass = 'table-warning';
                } else {
                    $rowClass = 'table-success';
                }

                $html .= '<tr class="' . $rowClass . '">';
                $html .= '<td>' . ($index + 1) . '</td>';
                $html .= '<td>' . ($visit->id ?? '--') . '</td>';
                $html .= '<td>' . ($visit->customer->name ?? '--') . '</td>';
                $html .= '<td>' . ($visit->start_time ? \Carbon\Carbon::parse($visit->start_time)->format('h:i A') : '--') . '</td>';
                $html .= '<td>' . ($visit->end_time ? \Carbon\Carbon::parse($visit->end_time)->format('h:i A') : '--') . '</td>';
                $html .= '<td>' . ($visit->cleaner->name ?? '--') . '</td>';
                $html .= '<td>' . ($visit->receptionist->name ?? '--') . '</td>';

                // Subscription package type and description
                $packageType = '--';
                $packageDescription = '';
                if (isset($visit->subscription) && isset($visit->subscription->package)) {
                    $packageType = __('translations.' . ($visit->subscription->package->type ?? 'not exist'));
                    if (!empty($visit->subscription->package->description)) {
                        $packageDescription = '<small class="d-block text-muted">' . $visit->subscription->package->description . '</small>';
                    }
                }
                $html .= '<td>' . $packageType . $packageDescription . '</td>';

                $html .= '<td>' . ($visit->execution_date ? \Carbon\Carbon::parse($visit->execution_date)->format('Y-m-d h:i A') : __('translations.not_executed')) . '</td>';
                $html .= '<td>' . ($visit->return_date ? \Carbon\Carbon::parse($visit->return_date)->format('h:i A') : '--') . '</td>';
                $html .= '<td>' . ($visit->amount ?? '--') . '</td>';
                $html .= '<td>' . ($visit->location ?? '--') . '</td>';
                $html .= '<td>' . ($visit->notes ?? '--') . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html = '<tr><td colspan="13" class="empty-state"><i class="fas fa-sun"></i><br>لا توجد زيارات صباحية لهذا اليوم</td></tr>';
        }
        return $html;
    }

    public function setLocale(Request $request)
    {
        $locale = $request->input('locale');
        app()->setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
}
