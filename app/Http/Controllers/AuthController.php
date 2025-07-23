<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('Auth.register');
    }

    public function registerPost(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Mail::to($request->email)->send(new WelcomeMail($user));
        return redirect()->route('login')->with('success', 'User registered successfully');
    }

    public function dashboard(Request $request)
    {
        return view('dashboard');
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully');
    }

    public function login(Request $request)
    {
        return view('Auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->route('posts.index')->with('success', 'Logged in successfully');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
