<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * Register a new user
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function register(UserRequest $request) : UserResource
    {
        return UserResource::make(
            User::create($request->validated())
        );
    }

    /**
     * Login a user
     *
     * @param LoginRequest $request
     * @return AuthResource
     */
    public function login(LoginRequest $request) : AuthResource
    {
        $request->validated();
        /**
         * @var User
         */
        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return abort(401, "Email ou mot de passe incorrects");
        }
        
        return AuthResource::make($user);
    }


    /**
     * Logout a user
     *
     * @return boolean
     */
    public function logout() : bool
    {
        $user = $this->currentUser();    
        $user->token()?->revoke();
        return true;
    }


    /**
     * Get the authenticated user
     *
     * @return AuthResource
     */
    public function me() : AuthResource
    { 
        return AuthResource::make($this->currentUser());
    }


    /**
     * Refresh the token
     *
     * @return AuthResource
     */
    public function refresh() : AuthResource
    {
        return AuthResource::make($this->currentUser());
    }


    /**
     * Update the authenticated user
     *
     * @param UserRequest $request
     * @return AuthResource
     */
    public function update(UserRequest $request) : AuthResource
    {
        $user = $this->currentUser();
        $user->update($request->validated());
        return AuthResource::make($user);
    }


    /**
     * Delete the authenticated user
     *
     * @return boolean
     */
    public function destroy() : bool
    {
        $user = $this->currentUser();
        $user->delete();
        return true;
    }   
     

    /**
     * Get the authenticated user
     *
     * @return User
     */
    public function currentUser() : User
    {
        return User::find(Auth::user()->id);
    }
}
