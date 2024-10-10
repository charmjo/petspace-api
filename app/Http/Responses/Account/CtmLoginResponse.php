<?php

namespace App\Http\Responses\Account;

// other dependent classes
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;

class CtmLoginResponse implements LoginResponse {
    public function toResponse($request)
    {
        Log::debug("user".Auth::user());
        Log::debug("user name".Auth::user()->email);

        $userDetail = Auth::user();
            return response()->json(
            ['two_factor' => false,
                'user' => [
                'name'=> $userDetail->name,
                'email'=> $userDetail->email
                ]
            ]
        );
    }
}