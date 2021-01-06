<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'v1',
], function () {
    // Auth::routes() without Blade views
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


});

Route::middleware('auth:sanctum')->get('/usr', function (Request $request) {
    return $request->user();
});


// -------------------------------------------------------------------------------------------------
// Это нужно воплотить во Vue

//Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
//
//Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
//
//Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm']);
//
//Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm']);
//
//Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
//
//Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');

// -------------------------------------------------------------------------------------------------
