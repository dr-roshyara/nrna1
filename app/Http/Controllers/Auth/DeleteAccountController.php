<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DeleteAccountController extends Controller
{
    /**
     * Delete the authenticated user's account
     */
    public function delete(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Verify password
        if (!Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('auth.password')],
            ]);
        }

        // Delete user
        $user = $request->user();
        $user->delete();

        // Logout
        auth()->logout();

        return redirect('/')->with('status', 'Account deleted successfully.');
    }
}
