<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:tutor,student',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        // `password` is casted to 'hashed' in the model, so assign plain password
        $user->password = $data['password'];
        $user->role = $data['role'];
        $user->save();

        Auth::login($user);

      if ($user->role === 'tutor' || $user->role === 'student') {
    return redirect()->away('https://findstudyroom.com/');
}

        return redirect('/')->with('success', 'Registration successful');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // If a redirect target was saved (intended), prefer it unless it's an admin URL
            $intended = $request->session()->pull('url.intended');
            if ($intended && !Str::contains(parse_url($intended, PHP_URL_PATH) ?: $intended, '/admin')) {
                return redirect()->to($intended);
            }

            $user = Auth::user();
          if ($user->role === 'tutor' || $user->role === 'student') {
    return redirect()->away('https://findstudyroom.com/');
}
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
