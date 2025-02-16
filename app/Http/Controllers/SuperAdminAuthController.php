<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;

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

        // Validate the request fields
        $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required',
        ]);
    
        // Attempt to find the superadmin with the given email
        $superAdmin = SuperAdmin::where('admin_email', $request->admin_email)->first();
    
        // Check if the superadmin exists and the password is correct
        if ($superAdmin && Hash::check($request->admin_password, $superAdmin->admin_password)) {
            Auth::guard('superadmin')->login($superAdmin);
            return redirect()->route('superadmin.profile');
        }

        // If credentials are invalid, return back with error
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
