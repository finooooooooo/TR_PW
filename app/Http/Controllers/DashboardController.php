<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentMonth = now()->format('Y-m');

        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $totalTenants = Tenant::count();
        $activeLeases = Lease::where('status', 'active')->count();
        $monthlyIncome = Payment::where('status', 'paid')
            ->where('period_month', $currentMonth)
            ->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();

        $recentPayments = Payment::with(['lease.room', 'lease.tenant'])
            ->latest('payment_date')
            ->take(5)
            ->get();

        $expiringLeases = Lease::with(['room', 'tenant'])
            ->where('status', 'active')
            ->whereBetween('end_date', [today()->toDateString(), today()->addDays(30)->toDateString()])
            ->orderBy('end_date')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'totalTenants',
            'activeLeases',
            'monthlyIncome',
            'pendingPayments',
            'recentPayments',
            'expiringLeases',
            'currentMonth'
        ));
    }
}
