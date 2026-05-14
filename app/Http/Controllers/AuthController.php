<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Default credentials (replace with DB auth as needed)
    private const DEFAULT_USERNAME = 'admin';
    private const DEFAULT_PASSWORD = 'admin123';

    public function showLogin()
    {
        if (Session::get('logged_in')) {
            return redirect()->route('targets.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Simple credential check — swap with Auth::attempt() for real users
        if (
            $request->username === self::DEFAULT_USERNAME &&
            $request->password === self::DEFAULT_PASSWORD
        ) {
            Session::put('logged_in', true);
            Session::put('username', $request->username);
            return redirect()->route('targets.index');
        }

        return back()->with('error', 'Invalid username or password. Please try again.');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
