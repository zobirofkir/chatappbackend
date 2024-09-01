<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : AnonymousResourceCollection
    {
        return UserResource::collection(
            User::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): UserResource
    {
        $data = $request->validated();
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $user = User::create($data);    
        return UserResource::make($user);
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
    public function update(UserRequest $request, User $user): UserResource
    {
        $data = $request->validated();
    
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
    
            $data['image'] = $request->file('image')->store('images', 'public');
        }
    
        $user->update($data);
    
        return UserResource::make($user->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) : bool
    {
        return $user->delete();
    }
}
