<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // Add this import
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SuperAdminAuthController extends Controller
{
    /**
     * Show the Super Admin Registration Form
     */
    public function showRegisterForm()
    {
        return view('super-admin.register');
    }

    /**
     * Handle Super Admin Registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:super_admins,admin_email',
            'admin_date_of_birth' => 'required|date',
            'admin_phoneNumber' => 'required|string|max:20',
            'admin_place' => 'required|string|max:255',
            'admin_password' => 'required|min:6|confirmed',
            'admin_nid' => 'required|string|unique:super_admins,admin_nid',
        ]);

        // Create Super Admin
        $superAdmin = SuperAdmin::create([
            'admin_name' => $request->admin_name,
            'admin_email' => $request->admin_email,
            'admin_date_of_birth' => $request->admin_date_of_birth,
            'admin_role' => 'super admin',
            'admin_phoneNumber' => $request->admin_phoneNumber,
            'admin_place' => $request->admin_place,
            'admin_password' => Hash::make($request->admin_password),
            'admin_nid' => $request->admin_nid,
        ]);

        Auth::guard('superadmin')->login($superAdmin);
        return redirect()->route('superadmin.profile');
    }

    /**
     * Show the Super Admin Login Form
     */
    public function showLoginForm()
    {
        return view('super-admin.login');
    }

    /**
     * Handle Super Admin Login
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required',
        ]);
    
        // Attempt to authenticate using the super_admin guard
        if (Auth::guard('superadmin')->attempt([
            'admin_email' => $request->admin_email,
            'password' => $request->admin_password // It will check the hashed password
        ])) {
            // If authentication is successful, regenerate the session
            $request->session()->regenerate();
    
            // Redirect to the Super Admin profile
            return redirect()->route('superadmin.profile');
        }
    
        // If authentication fails, return with an error message
        return back()->withErrors(['admin_email' => 'Invalid credentials.']);
    }
    

    
    /**
     * Show Super Admin Profile
     */
    public function profile()
    {
        return view('super-admin.profile');
    }

    /**
     * Handle Logout
     */
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin.login');
    }
}
