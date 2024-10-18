<?php
namespace App\Http\Responses\Account;

// other dependent classes
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\Auth;

class CtmRegisterResponse implements RegisterResponse {
    public function toResponse($request)
    {
        $userDetail = Auth::user();
        return response()->json(
        ['user' => [
            'id'=> $userDetail->id,
            'first_name'=> $userDetail->first_name,
            'last_name'=> $userDetail->last_name,
            'email'=> $userDetail->email
         ]
        ]);
    }
}