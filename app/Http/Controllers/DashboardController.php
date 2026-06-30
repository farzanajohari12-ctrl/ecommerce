<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Direct Admin profiles to their performance/analytics dashboard
        if ($user->hasRole('admin')) {
            return view('dashboards.admin', [
                'totalSales'  => 152500, 
                'activeUsers' => 45
            ]);
        }

        // 2. Direct Staff members to their operational/task queue dashboard
        if ($user->hasRole('staff')) {
            return view('dashboards.staff', [
                'pendingOrders' => 14,
                'lowStockAlerts' => 3
            ]);
        }

        // 3. Fallback: Direct standard customers/users to their profile dashboard
        return view('dashboards.user', [
            'myRecentOrders' => []
        ]);
    }
}
