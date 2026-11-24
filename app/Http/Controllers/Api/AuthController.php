<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Login with phone number and password
     * On first login, returns password reset required flag
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['Invalid phone number or password.'],
            ]);
        }

        // Check if password change is required (first login)
        if ($user->password_change_required) {
            return response()->json([
                'status' => 'success',
                'message' => 'Password change required',
                'password_change_required' => true,
                'user_id' => $user->id,
                'temporary_token' => $user->createToken('temp-password-reset')->plainTextToken,
            ], 200);
        }

        // Regular login - password already changed
        $token = $user->createToken('library-app-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'password_change_required' => false,
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Reset password on first login
     * Requires temporary token from login endpoint
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|required_without:phone|exists:users,id',
            'phone' => 'nullable|required_without:user_id|string',
            'current_password' => 'nullable|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user_id
            ? User::findOrFail($request->user_id)
            : User::where('phone', trim($request->phone))->firstOrFail();

        $accessToken = $request->bearerToken()
            ? PersonalAccessToken::findToken($request->bearerToken())
            : null;

        $tokenBelongsToUser = $accessToken && $accessToken->tokenable_id === $user->id;
        $currentPasswordIsValid = $request->filled('current_password')
            && Hash::check($request->current_password, $user->password);

        if (! $tokenBelongsToUser && ! $currentPasswordIsValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Temporary token or current password is required.',
            ], 401);
        }

        // Update password and mark password_change_required as false
        $user->update([
            'password' => Hash::make($request->new_password),
            'password_change_required' => false,
        ]);

        if ($accessToken) {
            $accessToken->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successful. Please login with your new password.',
        ], 200);
    }

 


   
    public function me(Request $request)
    {
        $user = $request->user()->load('library');
        
        if ($user->library) {
            // Fix closing time format
            if (isset($user->library->close_time)) {
                if (preg_match('/^(\d+)(:(\d+))?/', $user->library->close_time, $matches)) {
                    $hour = (int)$matches[1];
                    $minute = isset($matches[3]) ? $matches[3] : '00';
                    if ($hour < 12 && $hour > 0) {
                        $hour += 12;
                    }
                    $user->library->close_time = sprintf('%02d:%02s:00', $hour, $minute);
                }
            }
            
            // Add total_capacity from LibraryDetail
            $libraryDetail = \App\Models\LibraryDetail::where('library_id', $user->library->id)->first();
            if ($libraryDetail && isset($libraryDetail->capacity)) {
               // $user->library->setAttribute('capacity', $libraryDetail->capacity);
                $user->library->setAttribute('total_capacity', $libraryDetail->capacity);
            } else {
                $user->library->setAttribute('total_capacity', $user->library->capacity ?? null);
            }

            // Fix duplicate facilities
            if (isset($user->library->facilities) && is_array($user->library->facilities)) {
                $user->library->facilities = array_values(array_unique($user->library->facilities));
            }

            // Fix owner name (use user's name instead of library's owner_name if it was set incorrectly)
            $user->library->owner_name = $user->name;
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
    /**
     * Change password (after user is logged in)
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully.',
        ], 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.',
        ], 200);
    }

    /**
     * Get current user
     */
    public function user(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user(),
        ], 200);
    }
    /**
     * Get plans according to auth user (from Plan model via LibraryDetail)
     */
    public function getUserPlans(Request $request)
    {
        $user = $request->user();
        
        if ($user && $user->library_id) {
            $libraryDetail = \App\Models\LibraryDetail::with('plan')->where('library_id', $user->library_id)->first();
            
            return response()->json([
                'status' => 'success',
                'active_plan' => $libraryDetail ? $libraryDetail->plan : null,
                'library_detail' => $libraryDetail,
                
            ], 200);
        }
        
        return response()->json([
            'status' => 'success',
            'active_plan' => null,
            'library_detail' => null,
            'all_plans' => \App\Models\Plan::all(),
        ], 200);
    }
    
    
    
       public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        
        if ($user) {
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'FCM token saved successfully',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
        ], 404);
    }
    
     public function deleteFcmToken(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            $user->fcm_token = null;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'FCM token deleted successfully',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',
        ], 404);
    }
}
