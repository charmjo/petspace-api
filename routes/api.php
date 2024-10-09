<?php

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
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // restrict user to only one token
    if($user->tokens()->count() >= 1){
        $user->tokens()->delete();
    };

    $userToken = $user->createToken($request->device_name)->plainTextToken;

    return response()->json([
        'token' => $userToken
    ])->header('Content-Type', 'application/json');
});

// protected route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
