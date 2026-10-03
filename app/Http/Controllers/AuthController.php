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
        $request->session()->regenerate();
        ActivityLog::record(Auth::user(), 'Authentication', 'login', 'User '.$request->user()->name.' berhasil login.');
        return redirect()->intended(route(Auth::user()->isOwner() ? 'dashboard' : 'cashier'));
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
        ActivityLog::record($user, 'Authentication', 'logout', 'User '.($user?->name ?? 'Unknown').' logout dari sistem.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}