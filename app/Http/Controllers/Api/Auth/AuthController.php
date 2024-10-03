<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            return response()->json([
                'status' => 200,
                'message' => 'User successfully registered',
                'data' => $user,
                'token' => $user->createToken("API Token")->plainTextToken,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'status' => 401,
                'message' => 'Email & Password do not match our records',
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged in',
            'data' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.'
                ], 404);
            }
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error occurred during logout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
    public function profile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
