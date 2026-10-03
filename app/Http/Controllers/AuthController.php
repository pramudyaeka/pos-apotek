<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(['email'=>'required|email','password'=>'required|string']);
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email'=>'Email atau password tidak sesuai.']);
        }
        if (Auth::user()->status !== 'Active') {
            Auth::logout();
            throw ValidationException::withMessages(['email'=>'Akun Anda sedang tidak aktif.']);
        }
        $user = $request->user();

        $request->session()->regenerate();

        // Pencatatan aktivitas tidak boleh menggagalkan autentikasi.
        try {
            ActivityLog::record($user, 'Authentication', 'login', 'Pengguna '.$user->name.' berhasil masuk.');
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->intended(
            $user->isOwner() ? route('dashboard') : route('orders')
        );
    }

    public function signup(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|max:255|unique:users,email','password'=>'required|confirmed|min:8']);
        User::create([...$data,'role'=>'Cashier','status'=>'Active']);
        return redirect('/')->with('success','Akun berhasil dibuat. Silakan masuk.');
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