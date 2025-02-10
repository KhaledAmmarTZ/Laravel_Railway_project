<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|unique:admins',
            'admin_date_of_birth' => 'required|date',
            'admin_role' => 'required|in:super_admin,admin',
            'admin_phoneNumber' => 'required|string|max:15',
            'admin_place' => 'required|string|max:255',
            'admin_password' => 'required|string|min:6|confirmed',
            'admin_nid' => 'required|string|unique:admins',
        ]);

        $admin = Admin::create([
            'admin_name' => $request->admin_name,
            'admin_email' => $request->admin_email,
            'admin_date_of_birth' => $request->admin_date_of_birth,
            'admin_role' => $request->admin_role,
            'admin_phoneNumber' => $request->admin_phoneNumber,
            'admin_place' => $request->admin_place,
            'admin_password' => Hash::make($request->admin_password),
            'admin_nid' => $request->admin_nid,
        ]);

        return response()->json(['message' => 'Admin registered successfully!', 'admin' => $admin], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'admin_email' => 'required|email',
            'admin_password' => 'required|string',
        ]);

        $admin = Admin::where('admin_email', $request->admin_email)->first();

        if (!$admin || !Hash::check($request->admin_password, $admin->admin_password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
