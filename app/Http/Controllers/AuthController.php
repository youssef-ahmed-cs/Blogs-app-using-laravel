<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthManagement\AuthLoginRequest;
use App\Http\Requests\AuthManagement\AuthRegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register()
    {
        return view('Auth.register');
    }

    public function registerPost(AuthRegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        //Mail::to($request->email)->send(new WelcomeMail($user));
        return to_route('login');
    }

    public function dashboard(Request $request)
    {
        $count_likes = auth()->user()->likes()->count();
        $count_posts = auth()->user()->posts()->count();
        $count_comments = auth()->user()->comments()->count();
        return view('dashboard', ['count_posts' => $count_posts, 'count_comments' => $count_comments, 'count_likes' => $count_likes]);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('posts.index')->with('success', 'You have been logged out');
    }

    public function login(Request $request)
    {
        return view('Auth.login');
    }

    public function loginPost(AuthLoginRequest $request)
    {
        $request->validated();
        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->route('posts.index')->with('success', 'Logged in successfully');
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
