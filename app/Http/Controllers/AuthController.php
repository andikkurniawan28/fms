<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'is_active' => 1, // 🔥 filter aktif
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended();
        }

        return back()->with('error', 'Invalid credentials or your account is inactive.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function changePassword()
    {
        return view('changePassword');
    }

    public function changePasswordProcess(Request $request)
    {
        $request->validate([
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.required'     => 'Password baru wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user = Auth::user();

        // Update password baru dengan bcrypt()
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }

}
