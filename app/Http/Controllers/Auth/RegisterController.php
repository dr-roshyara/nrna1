<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Placeholder for registration logic
        // TODO: Implement full registration workflow
        return redirect('/login')->with('message', 'Registration functionality coming soon');
    }
}
