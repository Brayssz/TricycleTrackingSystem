<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('status', 'active')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Invalid credentials']);
        }

        Auth::login($user);

        return view('contents.dashboard')->with('success', 'Login successful!');

        // if (Auth::user()->position == 'admin') {
        //     return redirect()->route('users')->with('success', 'Login successful!');
        // } elseif (Auth::user()->position == 'clerk') {
        //     return redirect()->route('receive-requests')->with('success', 'Login successful!');
        // }

        // return redirect()->route('users')->with('success', 'Login successful!');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
