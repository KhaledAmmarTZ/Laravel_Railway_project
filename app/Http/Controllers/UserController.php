<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile()
    {
        return view('passenger'); // Ensure 'resources/views/user/profiles.blade.php' exists
    }
}
