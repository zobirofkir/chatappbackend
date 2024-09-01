<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function register(UserRequest $request): UserResource
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $user = User::create($data);

        return UserResource::make($user);
    }

    /**
     * Login a user
     *
     * @param LoginRequest $request
     * @return AuthResource|JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse|AuthResource
    {
        $request->validated();

        /**
         * @var User
         */
        $user = User::where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password incorrect'], 401);
        }

        return AuthResource::make($user);
    }

    /**
     * Logout a user
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        $user->token()?->revoke();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the authenticated user
     *
     * @return AuthResource
     */
    public function me(): AuthResource
    {
        $user = User::find(Auth::user()->id);
        return AuthResource::make($user);
    }

    /**
     * Refresh the token
     *
     * @return AuthResource
     */
    public function refresh(): AuthResource
    {
        $user = User::find(Auth::user()->id);
        return AuthResource::make($user);
    }

    /**
     * Update the authenticated user
     *
     * @param UserRequest $request
     * @return AuthResource
     */
    public function update(UserRequest $request): AuthResource
    {
        $user = User::find(Auth::user()->id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $user->update($data);

        return AuthResource::make($user);
    }

    /**
     * Delete the authenticated user
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }
        $user->delete();

        return response()->json(['message' => 'User successfully deleted']);
    }
}
