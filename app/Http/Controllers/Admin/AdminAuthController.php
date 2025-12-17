<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 尝试登录
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // 不是 admin → 强制登出
            if (!Auth::user()->is_admin) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You are not authorized to access admin panel.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
