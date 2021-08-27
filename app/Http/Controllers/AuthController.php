<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function handlerLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:6|max:20'
        ]);
        $credentials = $request->only('email', 'password');
        $remember = !empty($request->only('remember'));

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function handlerLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
