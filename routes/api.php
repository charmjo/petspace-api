<?php

use App\Http\Controllers\TokenAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;

// my custom controllers
use App\Http\Controllers\UserMobileController;
use Laravel\Fortify\RoutePath;


// unprotected routes:
Route::post(RoutePath::for('create-user','/create-user'), [UserMobileController::class, 'store']);

// now, how do I transfer this to another controller?. Ai waitttt... I can just 
Route::post('/login-token', [TokenAuthController::class,'generateToken']);

// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
