<?php
namespace App\Http\Responses\Account;

// other dependent classes
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\Auth;

class CtmRegisterResponse implements RegisterResponse {
    public function toResponse($request)
    {
        $userDetail = Auth::user();
        return response()->json(
        ['user' => [
            'name'=> $userDetail->name,
            'email'=> $userDetail->email
         ]
        ]);
    }
}