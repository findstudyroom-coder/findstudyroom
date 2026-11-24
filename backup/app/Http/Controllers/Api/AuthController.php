<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $request->validate([
        //     'phone' => 'required|string',
        //     'password' => 'required',
        // ]);

        // Find user by phone (allowing raw formatting support)
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $user = User::with('library')->whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') LIKE ?", ["%$phone%"])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid mobile number or password.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'library_name' => $user->library ? $user->library->name : 'N/A',
                ]
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('library');
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $user = User::whereRaw("REGEXP_REPLACE(phone, '[^0-9]', '') LIKE ?", ["%$phone%"])->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully.'
        ]);
    }
}
