<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Cleaner;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class DahboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-list', ['only' => ['index']]);
    }

    public function index()
    {
        // Get total counts

        $usersCount = User::count();



        // Get this week's bookings



        return view('admin.dashboard', compact(
           
            'usersCount',

        ));
    }
}
