<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RegController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/', [MainController::class, 'index']);

    Route::get('/edit/{id?}', [MainController::class, 'showEditPage'])->middleware('admin');
    Route::post('/edit/{id?}', [UserController::class, 'handlerEditPage']);

    Route::get('/security/{id?}', [MainController::class, 'showSecurityPage'])->middleware('admin');
    Route::post('/security/{id?}', [UserController::class, 'handlerSecurityPage']);

    Route::get('/status/{id?}', [MainController::class, 'showStatusPage'])->middleware('admin');
    Route::post('/status/{id?}', [UserController::class, 'handlerStatusPage']);

    Route::get('/avatar/{id?}', [MainController::class, 'showAvatarPage'])->middleware('admin');
    Route::post('/avatar/{id?}', [UserController::class, 'handlerAvatarPage']);

    Route::get('/create_user/{id?}', [MainController::class, 'showCreateUserPage'])->middleware('admin');
    Route::post('/create_user', [UserController::class, 'handlerCreateUser']);

    Route::get('/logout', [AuthController::class, 'handlerLogout']);

    Route::get('/profile/{id?}', [MainController::class, 'showProfilePage']);

    Route::get('/delete/{id?}', [UserController::class, 'deleteUser'])->middleware('admin');
});

Route::middleware('guest')->group(function() {
    Route::get('/login', [MainController::class, 'showLoginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'handlerLogin']);

    Route::get('/registration', [MainController::class, 'showRegistrationPage']);
    Route::post('/registration', [RegController::class, 'handlerRegistration']);
});
