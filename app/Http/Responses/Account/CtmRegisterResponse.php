<?php
namespace App\Http\Responses\Account;

// other dependent classes

use App\Http\Resources\Account\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\Auth;

class CtmRegisterResponse implements RegisterResponse {
    public function toResponse($request)
    {
        $authUserId = Auth::id();
        $userDetail = User::with('address')
                            -> withCount('pets')
                            ->findOrFail($authUserId);

        Log::debug($userDetail);

            return response()->json(
            ['two_factor' => false,
                'data' => new UserResource($userDetail)
            ]
        );
    }
}