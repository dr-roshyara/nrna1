<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('myApiToken');

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request){
        if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
        'message' => 'Invalid login details'
                ], 401);
            }

        // return "test";
        // $user = User::where('email','=','roshyara@gmail.com')->firstOrFail();
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('myApiToken'); //Correct

        // $token =$user->createToken('myapptoken')->planeTextToken;
        $response= [
            'user'=>$user,
            'token'=>$token,
             'token_type' => 'Bearer',
        ];
        return response($response, 201);
    }
}
