<?php

namespace App\Http\Responses\Account;

// other dependent classes

use App\Http\Resources\Account\UserResource;
use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CtmLoginResponse implements LoginResponse {
    public function toResponse($request)
    {

        $authUserId = Auth::id();
        $userDetail = User::with('address')
                            -> withCount('pets')
                            ->findOrFail($authUserId);

            return response()->json(
            ['two_factor' => false,
                'data' => new UserResource($userDetail)
            ]
        );
    }
}