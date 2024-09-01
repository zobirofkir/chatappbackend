<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;


/**
 * Auth routes
 */
Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);


/**
 * Password reset
 */
Route::get('password/reset', function () {
    return view('emails.reset-password');
})->name('password.reset.form');
Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');



/**
 * API routes
 */
Route::middleware("auth:api")->group(function() {

    /**
     * User routes
     */
    Route::apiResource("/auth/users", UserResource::class);

    /**
     * Logout Route
     */
    Route::post("/auth/logout", [AuthController::class, "logout"]);

    /**
     * Me Route
     */
    Route::get("auth/me", [AuthController::class, "me"]);
    
    /**
     * Refresh Route
     */
    Route::post("/auth/refresh", [AuthController::class, "refresh"]);

    /**
     * Update Route
     */
    Route::post("/auth/update", [AuthController::class, "update"]);

    /**
     * Destroy Route
     */
    Route::post("/auth/destroy", [AuthController::class, "destroy"]);

    /**
     * Conversation routes
     */
    Route::apiResource("/conversations", ConversationController::class);
});
