<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Role;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        // Fetch all roles from the database
        $role = Role::all();
        // Check if the user is already logged in
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user is an admin
            if ($user->role === 'admin') {
                return redirect('admin/user'); // Redirect to a specific admin dashboard
            }

            return redirect('pegawai'); // Redirect for other users
        }
            // If the user is not logged in, return the login view with roles
        return view('login', compact('role')); // Pass roles to the login view
        
    }

    public function actionlogin(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'role' => 'required|string|max:255',
        ]);
    
        // Prepare login credentials
        $data = [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
        ];
    
        // Attempt login
        if (Auth::attempt($data)) {
            $user = Auth::user();
    
            // Check if the user role matches the requested role
            if ($user->role === $request->input('role')) {
                // Check if the user is admin
                if ($user->role === 'admin') {
                    return redirect('admin/user');
                } else {
                    return redirect('pegawai');
                }
            } else {
                // Logout the user if role does not match
                Auth::logout();
                Session::flash('error', 'Role is incorrect.');
                return redirect('/');
            }
        } else {
            // Flash an error message if authentication fails
            Session::flash('error', 'Username atau Password Salah');
            return redirect('/');
        }
    }
    
    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}
