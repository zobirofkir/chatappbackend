<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : AnonymousResourceCollection
    {
        return UserResource::collection(
            User::paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request) : UserResource
    {
        return UserResource::make(
            User::create($request->validated())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) : UserResource
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user) : UserResource
    {
        $user->update($request->validated());
        return UserResource::make(
            $user->refresh()
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) : bool
    {
        return $user->delete();
    }
}
