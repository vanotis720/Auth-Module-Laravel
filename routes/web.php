<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

// post login data
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

// social login
// Route::get("login-register", "SocialiteController@loginRegister");

// La redirection vers le provider
Route::get("redirect/{provider}", [AuthController::class, 'redirect'])->name('socialite.redirect');

// Le callback du provider
Route::get("callback/{provider}", [AuthController::class, 'callback'])->name('socialite.callback');

Route::middleware('auth')->group(function () {

    Route::get('/', function() {
        return 'User is logged in';
    })->name('home');

    Route::get('/home', function() {
        return 'User is logged in';
    })->name('home');

    Route::get('/profil', [AuthController::class, 'getUser'])->name('profil');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});
