<?php
// I will not put a guard on this. I would like use passport for API tokens but I am a noob so this will have to do.
namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

// Fortify Validation and storage
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;

class UserMobileController extends Controller
{
    // why am I forced to create zis?
    /* 
        - Laravel fortify's registration contains a stateful guard which means, a cookie is needed to access this class.
        - Mobile tokens are cookie-less there will be an error accessing this route. so, I will have to MAKE MY. OWN.
        - unya nako maghuna-huna og protect once I got this thing to work!
    */
    public function store(Request $request,
        CreatesNewUsers $creator)
    {
        // anyways, createnewuser is just an impelementation of createsnewusers and is not beholden to anything so this should be fine???
        event(new Registered($user = $creator->create($request->all())));
        
        // will have to return a json token. I sincerely pray to god this works.
        // I need something to handle this
        return response()->json(
        ['message' => 'user is now registered'
        ]);
    }
}
