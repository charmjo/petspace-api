<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

// TODO:will need to test this in postman. Tests created are just for regression testing.
class EmailVerificationController extends Controller
{
    public function verify ($id,$hash) {
        $user = User::find($id);
        abort_if(!$user, 403);
        abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }
    
        return view('verified-account');
    }

    //TODO: Change the messaging later
    public function resendNotification (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return ["message"=>"email verification resent."];
    }
}
