<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function handlerEditPage(Request $request, $id)
    {
        $userData = $request->except('_token');
        User::where('id', $id)->update($userData);
        return redirect('/');
    }

    public function handlerSecurityPage(Request $request, $id)
    {
        $userData = $request->except('_token');
        Validator::make($userData, [
            'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'max:20'
            ]
        ],
            [
            'unique' => 'The :attribute already exists!'
            ])->validate();

        $userData['password'] = Hash::make($userData['password']);
        unset($userData['password_confirmation']);
        User::where('id', $id)->update($userData);
        Session::flash('success', "The user's data was changed successfully!");
        return redirect('/');
    }


    private function prepareStatus($status): string
    {
        if ($status === 'Онлайн') {
            return 'success';
        } elseif ($status === 'Отошел') {
            return 'warning';
        } elseif ($status === 'Не беспокоить') {
            return 'danger';
        }
    }


    public function handlerStatusPage(Request $request, $id)
    {
        $status = $request->online_status;
        $status = $this->prepareStatus($status);
        User::where('id', $id)->update(['online_status' => $status]);
        Session::flash('success', 'The status has been changed successfully!');
        return redirect('/');
    }

    public function handlerAvatarPage(Request $request, $id)
    {
        $request->validate(['file' => 'required|file|image']);
        if ($request->file('file')->isValid()) {
            $path = $request->file->store('uploads', 'local');
            User::where('id', $id)->update(['avatar_path' => $path]);
        }
        return redirect('/');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user->avatar_path != 'uploads/avatar-m.png') {
            Storage::delete($user->avatar_path);
        }
        User:: destroy(['id'=>$id]);
        Session::flash('success', 'The user has been successfully deleted!');
        return redirect('/');
    }

    public function handlerCreateUser(Request $request)
    {
        $userData = $request->except('_token');
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
        $userData['password'] = Hash::make($userData['password']);
        $userData['online_status'] = $this->prepareStatus($userData['online_status']);

        if(isset($userData['file'])) {
            $request->validate(['file' => 'required|file|image']);
            if ($request->file('file')->isValid()) {
                $userData['avatar_path'] = $request->file->store('uploads', 'local');
                unset($userData['file']);
            }
        }
        User::create($userData);
        Session::flash('success', 'The user has been created successfully!');
        return redirect('/');
    }


}
