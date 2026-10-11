<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password tidak sesuai.',
            ]);
        }

        if (Auth::user()->status !== 'Active') {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => 'Akun Anda sedang tidak aktif.',
            ]);
        }

        $user = $request->user();

        $request->session()->regenerate();

        return redirect()->intended(
            $user->isOwner() ? route('dashboard') : route('orders')
        );
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        try {
            if ($user) {
                ActivityLog::record($user, 'Authentication', 'logout', 'Pengguna '.$user->name.' keluar dari sistem.');
            }
        } catch (\Throwable $e) {
            report($e);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
