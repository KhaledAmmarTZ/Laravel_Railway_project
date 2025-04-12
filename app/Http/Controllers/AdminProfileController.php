<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class AdminProfileController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function edit(Request $request)
    {
        $admin = Auth::guard('admin')->user(); 
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the admin's profile information.
     */
    public function update(AdminProfileUpdateRequest $request)
    {
        $admin = Auth::guard('admin')->user(); 
    
        $validatedData = $request->validated();
    
        // If updating the image 
        if ($request->hasFile('admin_image')) {
            $imagePath = $request->file('admin_image')->store('admin_images', 'public');
            $validatedData['admin_image'] = $imagePath;
        }
    
        // Update admin profile
        $admin->update($validatedData);
    
        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }
    

    /**
     * Delete the admin's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('adminDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $admin = auth()->guard('admin')->user(); 

        if (!$admin) {
            return redirect()->back()->withErrors(['error' => 'Admin not found.']);
        }

        // Check if the admin has an image and delete it from both locations
        if ($admin->admin_image) {
            // Path for the file in storage
            $imagePath = 'storage/admin_images/' . $admin->admin_image;
            
            if(file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        Auth::guard('admin')->logout(); 

        $admin->delete(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
