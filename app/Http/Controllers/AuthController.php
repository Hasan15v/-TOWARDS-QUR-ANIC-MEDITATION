<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
class AuthController extends Controller
{
    
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate user
     */
  public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    \Log::info('Login attempt', $credentials);  // ✅ للـ debugging

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/dashboard')->with('success', 'You have been logged in successfully.');
    }

    // ❌ إذا فشل التسجيل
    return back()
        ->withErrors([
            'email' => ' The email or password is incorrect',
        ])
        ->onlyInput('email');
}

    /**
     * Show register form
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'user';

        User::create($validated);

        return redirect('/login')->with('success', 'done! You can now log in with your new account.');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
