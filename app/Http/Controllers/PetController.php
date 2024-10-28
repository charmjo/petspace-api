<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Pet\CreateNewPetRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Pet\PetCollection;

class PetController extends Controller
{
    //

    public function create (CreateNewPetRequest $request) {
        Log::debug('create pet request here: ');
        Log::debug($request);

        $petOwnerId = $request->user()->id;

        Pet::create(array_merge($request->validated(), ['pet_owner_id' => $petOwnerId]));
    }

    public function delete ($id) {
        Log::debug('delete pet here');

        $pet = Pet::find($id);

        //TODO: check if the auth user matches the owner ID.

        // delete the pet,
        $pet->delete();

        // return response of deleted user, frontend will deal with frontend things.
        Log::debug('pet deleted');
    }

    public function update (CreateNewPetRequest $request, $id) {
        Log::debug('update user here');

        // find pet by id
        $pet = Pet::find($id);

        //TODO: check if the auth user matches the owner ID.

        Log::debug($pet);

        // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $pet->update($request);

        //TODO: add success
        return new PetCollection($pet); 
    }

    public function getDetail ($id) {

        // find pet by id
        $pet = Pet::find($id);

        //TODO: check if the auth user matches the owner ID.

        
        if($pet != null) {
            return new PetCollection($pet);
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }

    // will need to put this, will need user id. The user ID can be derived from this list
    public function getList (Request $request) {
        Log::debug('get list here');

        // get id
        Log::debug($request);

        //$id = $request->input('user_id');
        $id = Auth::id();
        //TODO: will need to add linked pets if id is linked to another account to view pet data. kani, I still have to think how to write this logic...

        // find user by id
        // will all picture here in the near future...
        $pets = Pet::select('id', 'name', 'breed')
            ->where('pet_owner_id',$id)
            ->get();

        
        if($pets != null) {
            return new PetCollection($pets);
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }


}

