<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class TokenAuthController extends Controller
{
    //
    public function generateToken (Request $request) {
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
    }
}
