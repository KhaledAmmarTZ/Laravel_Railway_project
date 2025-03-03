<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile()
    {
        return view('user.profiles'); // Ensure 'resources/views/user/profiles.blade.php' exists
    }
}
