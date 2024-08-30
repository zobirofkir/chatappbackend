<?php

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

Route::apiResource("/users", UserResource::class);