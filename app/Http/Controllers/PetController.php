<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Pet\CreateNewPetRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Pet;

class PetController extends Controller
{
    //

    public function create (CreateNewPetRequest $request) {
        Log::debug('create pet request here: ');
        Log::debug($request);

        Pet::create($request->validated());
    }

    public function delete ($id) {
        Log::debug('delete pet here');

        $pet = Pet::find($id);

        // delete the pet,
        $pet->delete();

        // return response of deleted user, frontend will deal with frontend things.
        Log::debug('pet deleted');
    }

    public function update (CreateNewPetRequest $request, $id) {
        Log::debug('update user here');

        // find user by id
        $user = Pet::find($id);

        Log::debug($user);

        // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $user->update($request);

        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }

    public function getDetail ($id) {
        Log::debug('update user here');

        // find user by id
        $pet = Pet::find($id);

        
        if($pet != null) {
            return response()->json(
                ['data'=> $pet->toArray()]
            );
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }

    // will need to put this, will need user id. The user ID can be derived from this list
    public function getList (Request $request) {
        Log::debug('get list here');

        // get id
        Log::debug($request);

        $id = $request->input('user_id');

        //TODO: will need to add linked pets if id is linked to another account to view pet data. kani, I still have to think how to write this logic...

        // find user by id
        // will all picture here in the near future...
        $pets = Pet::select('id', 'name', 'breed')
            ->where('pet_owner_id',$id)
            ->get();

        
        if($pets != null) {
            return response()->json(
                ['data'=> $pets->toArray()]
            );
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }


}

