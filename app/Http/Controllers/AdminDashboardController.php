<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\LoginLog;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $userOnlyCount = User::where('role', 'user')->count();
        $latestUsers = User::latest()->take(5)->get();
        $query = LoginLog::with('user')->latest();

        // New users per day (last 7 days)
        $userStats = [];
        foreach (range(6, 0) as $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');
            $count = User::whereDate('created_at', $date)->count();
            $userStats['labels'][] = $date;
            $userStats['data'][] = $count;
        }

        // Recently active users (assuming updated_at is touched on login)
        $activeUsers = User::orderBy('updated_at', 'desc')->take(5)->get();

        // Login/logout logs
        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }
    
        $loginLogs = $query->take(100)->get(); // Or paginate()    

        return view('dashboard', compact(
            'userCount', 'adminCount', 'userOnlyCount',
            'latestUsers', 'userStats', 'activeUsers', 'loginLogs'
        ));
    }

    
}
