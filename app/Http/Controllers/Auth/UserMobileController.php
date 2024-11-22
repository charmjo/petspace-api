<?php
// I will not put a guard on this. I would like use passport for API tokens but I am a noob so this will have to do.
namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Fortify Validation and storage
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;


// token auth
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserMobileController extends Controller
{
    // why am I forced to create zis?
    /*
        - Laravel fortify's registration contains a stateful guard which means, a cookie is needed to access this class.
        - Mobile tokens are cookie-less there will be an error accessing this route. so, I will have to MAKE MY. OWN.
        - unya nako maghuna-huna og protect once I got this thing to work!
    */
    public function createUser(Request $request,
        CreatesNewUsers $creator)
    {
        // so, I need to return the validations here coz it is sure as hell not returning it.
        // anyways, createnewuser is just an impelementation of createsnewusers and is not beholden to anything so this should be fine???
        event(new Registered($user = $creator->create($request->all())));

        // will have to return a json token. I sincerely pray to god this works.
        return response()->json(
        ['message' => 'You are now registered.'
        ]);
    }

    // make sure validation is on another class
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
