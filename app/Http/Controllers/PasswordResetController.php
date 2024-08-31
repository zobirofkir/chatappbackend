<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Send reset link
     *
     * @param Request $request
     * @return boolean
     */
    public function sendResetLinkEmail(Request $request) : bool
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return true;
        }

        return false;
    }

    /**
     * Reset password
     *
     * @param ResetPasswordRequest $request
     * @return void
     */
    public function reset(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
    
        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
                $user->tokens()->delete();
            }
        );
    
        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => 'password_reset_success'], 200);
        }
    
        return response()->json(['status' => 'password_reset_failed'], 400);
    }    
}
