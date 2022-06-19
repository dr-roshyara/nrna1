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
    //
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
