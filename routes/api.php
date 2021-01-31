<?php

use App\Http\Controllers\Api\V1\Account\AccountController;
use App\Http\Controllers\Api\V1\ImageAccount\ImageAccountController;
use App\Http\Controllers\Api\V1\Offer\OfferController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\Request\RequestController;
use App\Http\Controllers\Api\V1\Response\ResponseController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\User\UserController;
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

Route::prefix('v1')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::middleware('verified')->group(function () {
            Route::delete('gallery-account/{id}', [ImageAccountController::class, 'destroy']);
            Route::get('requests/canceled', [RequestController::class, 'canceled']);
            Route::post('requests/{id}/cancel', [RequestController::class, 'cancel']);
            Route::post('requests/{id}/approve', [RequestController::class, 'approve']);
            Route::post('requests/{id}/resend', [RequestController::class, 'resend']);
            Route::get('offers/by-account/{account_id}', [OfferController::class, 'getByAccount']);
            Route::get('offers/my', [OfferController::class, 'ownIndex']);
            Route::apiResource('requests', RequestController::class);
            Route::apiResource('offers', OfferController::class);
            Route::apiResource('responses', ResponseController::class);
        });
        Route::post('email/resend', [VerificationController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.resend');
        Route::get('user', [UserController::class, 'show']);
    });
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::apiResource('projects', ProjectController::class);
    Route::post('accounts/{id}/refresh', [AccountController::class, 'refresh']);
    Route::apiResource('accounts', AccountController::class)
        ->except('store');

    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
//    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
//    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});

//Route::get('/{uri}', function () {
//    abort(404);
//    return "error";
//})->where('uri', '.*');

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
//Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');

// -------------------------------------------------------------------------------------------------
