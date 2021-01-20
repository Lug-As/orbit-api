<?php

use App\Http\Controllers\Api\V1\Account\AccountController;
use App\Http\Controllers\Api\V1\Offer\OfferController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\Request\RequestController;
use App\Http\Controllers\Api\V1\Response\ResponseController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\Api\V1\Users\Resources\UserResource;


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
    Route::middleware('auth:sanctum')->group(function () {
//        Route::delete('accounts/{id}/force', [AccountController::class, 'forceDestroy']);
//        Route::post('accounts/{id}/restore', [AccountController::class, 'restore']);
//        Route::get('accounts/trashed', [AccountController::class, 'ownTrashed']);
        Route::post('accounts/{id}/refresh', [AccountController::class. 'refreshInfo']);
        Route::apiResource('accounts', AccountController::class)
            ->except([
                'create'
            ]);
        Route::get('requests/canceled', [RequestController::class, 'canceled']);
        Route::post('requests/{id}/cancel', [RequestController::class, 'cancel']);
        Route::post('requests/{id}/approve', [RequestController::class, 'approve']);
        Route::post('requests/{id}/resend', [RequestController::class, 'resend']);
        Route::apiResource('requests', RequestController::class);
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('offers', OfferController::class);
        Route::get('offers/by-account/{account_id}', [OfferController::class, 'getByAccount']);
        Route::get('offers/my', [OfferController::class, 'ownIndex']);
        Route::apiResource('responses', ResponseController::class);
        Route::get('user', function (Request $request) {
            return UserResource::make($request->user());
        });
    });

    // Auth::routes() // without Blade views
    Route::middleware('guest')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
    });
//    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
//    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
//    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
//    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
});
Route::get('/', function () {
    return "Hello from API.";
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
//Route::get('email/verify', [\App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');

// -------------------------------------------------------------------------------------------------
