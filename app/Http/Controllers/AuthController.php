<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->roles->first()->name == 'pembantu') {
                return redirect()->route('dashboard-servant');
            } elseif ($user->roles->first()->name == 'majikan') {
                return redirect()->route('dashboard-employe');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return view('login');
    }

    public function auth(Request $request): RedirectResponse
    {
        $request->validate([
            'account' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($request->account, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $fieldType => $request->account,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->is_active == 1) {
                if ($user->hasRole('pembantu')) {
                    return redirect()->intended('/dashboard-servant');
                } elseif ($user->hasRole('majikan')) {
                    return redirect()->intended('/dashboard-employe');
                } else {
                    return redirect()->intended('/dashboard');
                }
            } else {
                Auth::logout();
                return back()->with('error', 'Akun belum aktif! Silahkan hubungi Admin..');
            }
        }

        return back()->with('error', 'Akun yang dimasukkan salah!');
    }
}
