<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class LoginController extends Controller
{
    /**
     * Login user
     *
     * Authenticate a user and return an access token for API access.
     *
     * @unauthenticated
     *
     * @bodyParam email string required The user's email address. Example: user@example.com
     * @bodyParam password string required The user's password. Example: password123
     *
     * @response 200 {
     *     "message": "Successfully logged in",
     *     "success": true,
     *     "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
     *     "data": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "user@example.com",
     *         "created_at": "2023-01-01T00:00:00.000000Z",
     *         "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     * }
     *
     * @response 401 {
     *     "message": "Wrong Credentials",
     *     "success": false
     * }
     *
     * @response 422 {
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "email": ["The email field is required."],
     *         "password": ["The password field is required."]
     *     }
     * }
     */
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

    /**
     * Get authenticated user
     *
     * Retrieve the currently authenticated user's information.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Successfully retrieved user",
     *     "success": true,
     *     "data": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "email": "user@example.com",
     *         "created_at": "2023-01-01T00:00:00.000000Z",
     *         "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     */
    public function user(Request $request) : JsonResponse {
        return success(
            'Successfully retrieved user',
            auth()->user()->toArray(),
            200
        );
    }
}
