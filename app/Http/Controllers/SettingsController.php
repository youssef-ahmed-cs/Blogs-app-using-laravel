<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('settings.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('settings.show')->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('settings.show')->with('success', 'تم تحديث كلمة المرور بنجاح');
    }

    public function destroy()
    {
        $user = Auth::user();
        
        // Delete user's posts, comments, likes, etc. (cascade should handle this)
        $user->delete();
        
        Auth::logout();
        
        return redirect()->route('login')->with('success', 'تم حذف الحساب بنجاح');
    }
}

