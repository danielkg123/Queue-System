<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    // Show the change password form
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    // Update the password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], // Validates current password
            'new_password' => ['required', 'string', 'min:3', 'confirmed'], // Password confirmation
        ]);

        // Update the password
        $user = Auth::user();

        // Check if the user has the 'admin' role
        if ($user->role === 'admin') {
            // Redirect to the 'user' route if the user is an admin
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('user');
        }

        // If not an admin, update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('home')->with('success', 'Password changed successfully.');
    }
}
