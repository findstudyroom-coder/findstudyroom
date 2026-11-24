<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Request\Auth\UserSignInRequest;
use App\Http\Request\Auth\ForgetPasswordRequest;
use App\Http\Request\Auth\EditRequest;
use App\Http\Request\Auth\EditPasswordRequest;
use App\Mail\AdminForgetPassword;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

use Carbon\Carbon;
use DB;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class LoginController extends BaseController
{

    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.AUTH.login');
    }
   
    public function signIn(Request $request)
    {
    //   dd( $request);

        try {
            $credentials = $request->only('email', 'password');
           // dd( $credentials);
           if (Auth::attempt($credentials)) {
                  
                    if (Auth::check() ) {
                        $request->session()->regenerate();
            
                        // Retrieve user's profile information
                        $profile = Admin::where('id', Auth::id())
                            ->select('name', 'image')
                            ->first();
            
                        $preName = "Admin";
                        $preImage = 'default.png';
            
                        if ($profile) {
                            $preName = $profile->name;
                            if (!empty($profile->image) && Storage::disk('public')->exists($profile->image)) {
                                $preImage = $profile->image;
                            }
                        }
            
                        // Store user's name and image in the session
                        $request->session()->put('admin.name', $preName);
                        $request->session()->put('admin.image', Storage::disk('public')->url($preImage));
            
                        return redirect()->route('dashboard')->withSuccess(__('Logged in successfully'));
                    } else {
                        // If the authenticated user is not an admin with role_id = 1, logout and return an error
                        Auth::logout();
                        return back()->withError(__('Something is wrong. Please try again.'));
                    }
                } else {
                    // If authentication fails, redirect back with an error message
                    return back()->withInput()->withError(__('Invalid credentials. Please try again.'));
                }
            } catch (\Throwable $th) {
                // Handle any unexpected errors
                dd($th);
                return back()->withWarning(__('Please try again.'));
            }
        return back()->withInput()->withError(__('Invalid login credential please try again'));
    }

    public function signOut(Request $request)
    {
        try {
            Auth::logout();
            Session::flush(); 
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.user.login')->withSuccess(__('You have been successfully logged out'));
        } catch (\Throwable $th) {
            return back()->withWarning(__('admin.error'));
        }

        return back()->withError(__('admin.logout_not'));
    }


    public function createForgetPassword()
    {
        try {
            $title = 'Forget password';

            return view('admin.AUTH.forgetpassword', compact(
                'title'
            ));
        } catch (\Throwable $th) {
            // dd($th);
            return back()->withWarning(__('admin.error'));
        }
    }

    public function storeForgetPassword(Request $request)
    {
        try {
            $user = DB::table('admins')->where('email', $request->email)->first();
    
            if (!$user) {
                return back()->with('error', 'User not found!');
            }
    
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid email format');
            }
    
            $otpNew = mt_rand(1000, 9999);
    
            // Update OTP
            DB::table('admins')->where('email', $user->email)->update(['otp' => $otpNew]);
    
            // Send OTP via mail
            $mail_data = [
                'email' => $user->email,
                'otp' => $otpNew,
            ];
            Mail::to($user->email)->send(new AdminForgetPassword($mail_data));
    
            // Debug the redirect
            return redirect()->route('admin.access.changePassword')->with('success', 'OTP sent successfully.');
        } catch (\Throwable $th) {
            //dd($th);
            return back()->with('error', 'Something went wrong!');
        }
    }
    
    

    public function editForgetPassword(Request $request)
    {
        try {
            $user =DB::table('admins')->count();
         //   dd(  $user);
            if ($user <= 0) {
                return redirect()->route('admin.user.login')->withError(__('admin.invalid_url'));
            }
            $title = 'Change password';
            return view('admin.AUTH.resetpassword', compact(
                'title',
                'user'
            ));
        } catch (\Throwable $th) {
            // dd($th);
            return back()->withWarning(__('admin.error'));
        }
    }

    public function updateForgetPassword(Request $request)
{
    $request->validate([
        'otp' => 'required',
        'password' => 'required|confirmed|min:6'
    ]);

    try {
        // Fetch the user by OTP
        $user = DB::table('admins')->where('otp', $request->otp)->first();

        if (!$user) {
            return back()->with('error', 'Please enter corect otp');
        }

        // Check if the new password is the same as the old one
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error', 'New password cannot be the same as the current password.');
        }

        // Update the password and reset the OTP
        DB::table('admins')->where('otp', $request->otp)->update([
            'password' => Hash::make($request->password),
            'otp' => null
        ]);

        return redirect()->route('admin.access.login')->with('success', 'Password changed successfully.');

    } catch (\Exception $e) {
        //dd($e);
        return back()->with('error', 'Something went wrong!');
    }
}

 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   



public function dashboard()
{
    // Total Libraries
    $totalLibraries = \App\Models\Library::count();

    // Verified Libraries
    $verifiedLibraries = \App\Models\Library::where('is_verified', 1)->count();

    // Total Students
    $totalStudents = \App\Models\Student::count();

    // Monthly Data (Current Year)
    $monthlyStudents = \App\Models\Student::select(
            \Illuminate\Support\Facades\DB::raw('MONTH(created_at) as month'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('total','month')
        ->toArray();

    $monthlyLibraries = \App\Models\Library::select(
            \Illuminate\Support\Facades\DB::raw('MONTH(created_at) as month'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('total','month')
        ->toArray();

    // Yearly Data
    $yearlyStudents = \App\Models\Student::select(
            \Illuminate\Support\Facades\DB::raw('YEAR(created_at) as year'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->groupBy('year')
        ->pluck('total','year')
        ->toArray();

    $yearlyLibraries = \App\Models\Library::select(
            \Illuminate\Support\Facades\DB::raw('YEAR(created_at) as year'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
        )
        ->groupBy('year')
        ->pluck('total','year')
        ->toArray();

    // Call Records for Dashboard
    $user = \Illuminate\Support\Facades\Auth::user();
    $isSuperAdmin = $user->hasRole('Super Admin') || $user->hasRole('Admin');
    
    $callsTodayQuery = \App\Models\LibraryCallLog::with('library')->whereDate('called_at', \Carbon\Carbon::today());
    if (!$isSuperAdmin) {
        $callsTodayQuery->where('admin_id', $user->id);
    }
    $callsToday = $callsTodayQuery->get();

    return view('admin.dashboard', compact(
        'totalLibraries',
        'verifiedLibraries',
        'totalStudents',
        'monthlyStudents',
        'monthlyLibraries',
        'yearlyStudents',
        'yearlyLibraries',
        'callsToday'
    ));
}
public function Profile(Request $request){

 

        try {
            $title = 'Profile';
            // $user = User::select('email','name','image')
            //             ->first();
            $user = Admin::first();

         return view('admin.admin-profile', compact(
                'title',
                'user'
            ));
        } catch (\Throwable $th) {
         dd($th);
            return back()->withWarning(__('admin.error'));
        }



}



public function updateprofile(EditRequest $request)
{
    try {
        $profile = Auth::user(); 

        if (!$profile) {
            return back()->withWarning(__('Your profile has not been updated successfully'));
        }

        if ($request->hasFile('image')) {
            $destinationPath = public_path('documents');

            // Check if the folder exists, if not, create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Delete old image if it exists
            if ($profile->image && file_exists($destinationPath . '/' . $profile->image)) {
                unlink($destinationPath . '/' . $profile->image);
            }

            $image = $request->file('image');
            $imageName = time() . '-' . $image->getClientOriginalName(); // Unique image name

            // Move the image to the public/documents folder
            $image->move($destinationPath, $imageName);

            // Save only the image name in the database
            $profile->image = $imageName;
        }

        $profile->name = $request->name;
        $profile->save();

        // Update session with the new profile data
        $request->session()->put('admin.image', $profile->image);
        $request->session()->put('admin.name', $profile->name);

        return back()->withSuccess(__('Profile edited successfully'));
    } catch (\Throwable $th) {
        //dd($th);
        return back()->withWarning(__('admin.error'));
    }
}


public function UpdateProfilePassword(EditPasswordRequest $request)
{
    try {
       // $user = User::where('role_id',1)->first();
        $user = Auth::user();
       
        if (!$user) {
            return back()->withWarning(__('Updated Not Found'));
        }

        if (!Hash::check($request->old, $user->password)) {
            return back()->withInput()->withWarning(__('Your current password does not match'));
        }
        
        if (Hash::check($request->new, $user->password)) {
            return back()->withInput()->withWarning(__('New password cannot be the same as the current password.'));
        }

        $newPasswordHashed = $request->new ;
        $user->password = $newPasswordHashed;
     
        $user->save();
       
        
         return redirect()->route('Profile')->with('success', 'Password changed successfully.');
      
    } catch (\Throwable $th) {
       // dd($  th);
        return back()->withWarning(__('not updated'));
    }
    
}








}
