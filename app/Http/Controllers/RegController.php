<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;



class RegController extends Controller
{
    public function handlerRegistration(Request $request)
    {
        $userData = $request->all();

        Validator::make($userData, [
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:users'
            ],
            'password' => [
                'required',
                'min:6',
                'max:20'
            ]
        ],
            [
                'unique' => 'The :attribute already exists!'
            ])->validate();

        $userData = [
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        User::create($userData);
        Session::flash('success', "Registration was completed successfully! An email has been sent to your email address to confirm your email");
        Session::flash('success1', "success");

        return redirect('/login');

    }
}
