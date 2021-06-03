<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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
// form for test
Route::get('login', function(){
    return view('login');
})->name('login');
Route::get('register', function(){
    return view('register');
});

Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

// Redirection to the provider
Route::get("redirect/{provider}", [AuthController::class, 'redirect'])->name('socialite.redirect');

// The provider callback
Route::get("callback/{provider}", [AuthController::class, 'callback'])->name('socialite.callback');

// reset password
Route::get('/forgot-password', function () {
    return view('password.forgot');
})->name('password.request');

Route::post('/forget-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'getPassword']);
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');



Route::middleware('auth')->group(function () {

    Route::get('/', function() {
        return (Auth::viaRemember()) ? 'User is logged in via remember' : 'user logged in';
    })->name('home');

    Route::get('/home', function() {
        return 'User is logged in';
    })->name('home');

    Route::get('profil', [AuthController::class, 'getUser'])->name('profil');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});
