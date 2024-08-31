<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;


Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);


Route::get('password/reset', function () {
    return view('emails.reset-password');
})->name('password.reset.form');

Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');

Route::middleware("auth:api")->group(function() {
    Route::apiResource("/auth/users", UserResource::class);
    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::get("auth/me", [AuthController::class, "me"]);
    Route::post("/auth/refresh", [AuthController::class, "refresh"]);
    Route::post("/auth/update", [AuthController::class, "update"]);
    Route::post("/auth/destroy", [AuthController::class, "destroy"]);
});
