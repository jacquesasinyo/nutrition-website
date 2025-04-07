<?php

namespace App\Http\Controllers;



namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Login Form
    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('food.index')->with('success', 'Giriş başarılı!');
        }

        return back()->withErrors(['email' => 'Geçersiz kimlik bilgileri.'])->withInput();
    }


    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Kayıt başarılı! Şimdi giriş yapabilirsiniz.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Çıkış yapıldı.');
    }
}
