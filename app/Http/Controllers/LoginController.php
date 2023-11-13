<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) : JsonResponse {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if(!auth()->attempt($credentials)) {
            return error('Wrong Credentials', 401);
        }

        $token = auth()->user()->createToken('auth_token');

        return response()->json([
            'message' => 'Successfully logged in',
            'success' => true,
            'token' => $token->plainTextToken,
            'data' => auth()->user(),
        ], 200);
    }

    public function user(Request $request) : JsonResponse {
        return success(
            'Successfully retrieved user',
            auth()->user(),
            200
        );
    }
}
