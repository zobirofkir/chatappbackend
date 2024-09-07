<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\SendResetLinkEmailJob;
use App\Jobs\ResetPasswordJob;
use Illuminate\Http\Request;

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

        // Use dispatch helper to queue the job
        dispatch(new SendResetLinkEmailJob($request->input('email')));

        return true;
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

        // Use dispatch helper to queue the job
        dispatch(new ResetPasswordJob($validated));

        return response()->json(['status' => 'password_reset_success'], 200);
    }    
}
