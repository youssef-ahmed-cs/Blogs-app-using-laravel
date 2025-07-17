<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

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
            'password' => Hash::make($request->password) // Hash the password
        ]);

        Mail::to($request->email)->send(new WelcomeMail($user));

        auth()->login($user);
        return redirect()->route('dashboard')->with('success', 'User registered successfully');

    }

    public function dashboard(Request $request)
    {
        return view('dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('register')->with('success', 'Logged out successfully');
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
            return redirect()->route('dashboard')->with('success', 'Logged in successfully');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
