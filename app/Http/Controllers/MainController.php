<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MainController extends Controller
{


    public function index(Request $request)
    {

        $show_by = $_GET['show_by'] ?? 6;

        $users = User::paginate($show_by);

        return view('users', [
            'users' => $users,
            'templateData' => $this->templateData('User'),
            'show_by' => $show_by,
            'linkEdit' => '/edit',
            'linkProfile' => '/profile',
            'linkSecurity' => '/security',
            'linkStatus' => '/status',
            'linkMedia' => '/avatar',
            'linkDelete' => '/delete',
            'linkCreateUser' => '/create_user/' . Auth::id(),
            'counter' => 0
        ]);
    }

    private function templateData(string $title)
    {
        return  $templateData = [
            'title' => $title,
            'linkHomePage' => '/',
            'emailLoginUser' => Auth::user()->email,
            'linkUserProfile' => '/profile/' . Auth::id(),
            'linkLogout' => '/logout',
            'linkHome' => '/',
            'linkAbout' => ''
        ];
    }

    public function showEditPage($id)
    {
        return view('edit', [
            'templateData' => $this->templateData('Edit'),
            'user' => User::find($id),
            'linkEdit' => "/edit/{$id}",
        ]);
    }

    public function showSecurityPage($id)
    {
        return view('security', [
            'templateData' => $this->templateData('Security'),
            'user' => User::find($id),
            'linkEdit' => "/security/{$id}",
        ]);
    }

    public function showStatusPage($id)
    {
        $user = User::find($id);

        if ($user->online_status === 'success') {
            $online = 'selected';
        } elseif ($user->online_status == 'warning') {
            $pause = 'selected';
        } else {
            $offline = 'selected';
        }

        return view('status', [
            'templateData' => $this->templateData('status'),
            'linkEdit' => "/status/{$id}",
            'online' => $online ?? null,
            'pause' => $pause ?? null,
            'offline' => $offline ?? null

        ]);
    }

    public function showAvatarPage($id)
    {

        return view('avatar', [
            'templateData' => $this->templateData('Avatar'),
            'user' => User::find($id),
            'linkEdit' => "/avatar/{$id}",
        ]);
    }

    public function showProfilePage($id)
    {

        return view('page_profile', [
            'templateData' => $this->templateData('Profile'),
            'user' => User::find($id),
        ]);

    }


    public function showCreateUserPage()
    {
        return view('create_user', [
            'templateData' => $this->templateData('Create User'),
            'linkCreate' => "/create_user",
        ]);
    }

    public function showRegistrationPage()
    {
        return view('page_register', [
            'linkLogin' => '/login',
            'linkReg' => "/registration",
            'linkHomePage' => '/'
        ]);
    }

    public function showLoginPage()
    {
        return view('page_login', [
            'registration' => '/registration',
            'linkLogin' => "/login",
            'linkHomePage' => '/'
        ]);

    }







}
