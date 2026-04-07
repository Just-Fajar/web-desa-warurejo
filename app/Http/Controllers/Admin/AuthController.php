<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    /**
     * Tampilkan form login admin
     * Route: GET /admin/login
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login process admin
     * - Validate email dan password
     * - Attempt login dengan guard 'admin'
     * - Support remember me checkbox
     * - Regenerate session untuk security
     * - Redirect ke dashboard jika berhasil
     * 
     * Route: POST /admin/login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::guard('admin')->user()->name);
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    /**
     * Handle logout admin
     * - Logout dari guard 'admin'
     * - Invalidate session
     * - Regenerate token untuk security (prevent CSRF)
     * 
     * Route: POST /admin/logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
