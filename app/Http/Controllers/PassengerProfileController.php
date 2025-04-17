<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PassengerProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $id = Auth::id();
        $user = User::find($id);
        return view('passenger.profile', compact('user'));
    }
    
    public function edit($id)
    {
        $user = User::find($id);
        return view('passenger.profile_edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uname' => ['required', 'string', 'max:255'],
            'uplace' => ['required', 'string', 'max:255'],
            // 'phone' => ['required', 'regex:/^01\d{9}$/'],
        ]);
        DB::table('new_users')->where('uid', Auth::id())->update([
            'uname' => $request->uname,
            'uplace' => $request->uplace,
            'phone' => $request->phone,
        ]);
        return redirect()->route('profile.show')->with('msg', 'Profile updated successfully');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
 

        $user = User::find(Auth::id());

        if ($user->images) { 
            $oldImagePath = public_path('images/' . $user->images);
 
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete previous image
            }
        }
 
        if ($image = $request->file('profile_image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            
        }
        $user->update(['images' => $profileImage]);
        return back()->with('success', 'Profile picture updated successfully.');
    }
}