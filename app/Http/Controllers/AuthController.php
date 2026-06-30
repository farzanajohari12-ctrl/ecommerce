<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\LoginLog;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process login form
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // ✅ Log only after successful login
            LoginLog::create([
                'user_id' => Auth::id(),
                'event' => 'login',
                'ip_address' => $request->ip(),
            ]);

            // Login success
            // Redirect to based on role
            if (Auth::user()->role === 'admin') {
                return redirect('/dashboard');
            } else {
                return redirect('/dashboard');
            }
        }

        // ❌ Failed login - try to find user by email (user_id might still be null)
        $user = User::where('email', $request->email)->first();

        LoginLog::create([
            'user_id' => $user ? $user->id : null,  // ✅ Prevents null constraint error
            'event' => 'failed_login',
            'ip_address' => $request->ip(),
        ]);

        // Login failed
        return back()->with('error', 'Invalid credentials');
    }

    // Show register form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Process register form
    public function register(Request $request)
    {
        // Validate user input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account created. Please login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();       // Destroy session
        $request->session()->regenerateToken();  // Prevent CSRF reuse

        return redirect('/login');
    }

}
