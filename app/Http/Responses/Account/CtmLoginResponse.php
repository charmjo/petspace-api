<?php

namespace App\Http\Responses\Account;

// other dependent classes
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;

class CtmLoginResponse implements LoginResponse {
    public function toResponse($request)
    {

        $userDetail = Auth::user();
            return response()->json(
            ['two_factor' => false,
                'data' => $userDetail
            ]
        );
    }
}