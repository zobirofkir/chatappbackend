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
    public function register(UserRequest $request) : UserResource
    {
        return UserResource::make(
            User::create($request->validated())
        );
    }

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

    public function logout() : bool
    {
        $user = $this->currentUser();    
        $user->token()?->revoke();
        return true;
    }

    public function me() : AuthResource
    { 
        return AuthResource::make($this->currentUser());
    }

    public function refresh() : AuthResource
    {
        return AuthResource::make($this->currentUser());
    }

    public function update(UserRequest $request) : AuthResource
    {
        $user = $this->currentUser();
        $user->update($request->validated());
        return AuthResource::make($user);
    }

    public function destroy() : bool
    {
        $user = $this->currentUser();
        $user->delete();
        return true;
    }   
     
    public function currentUser() : User
    {
        return User::find(Auth::user()->id);
    }
}
