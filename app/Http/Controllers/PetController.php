<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Pet\CreateNewPetRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Pet\Pet;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Pet\PetResource;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    //
    public function create (CreateNewPetRequest $request) {
        Log::debug('create pet request here: ');
        Log::debug($request);

        $petOwnerId = $request->user()->id;

        Pet::create(array_merge($request->validated(), ['pet_owner_id' => $petOwnerId]));
        return response()->json(["message"=>"Pet added successfully"],201);
    }

    public function delete ($id) {
        Log::debug('delete pet here');

        $pet = Pet::find($id);

        //TODO: check if the auth user matches the owner ID.

        // delete the pet,
        $pet->delete();

        // return response of deleted user, frontend will deal with frontend things.
        Log::debug('pet deleted');
        return response()->json(["message"=>"Pet removed successfully"],200);
    }

    public function update (CreateNewPetRequest $request) {
        // find pet by id
        $petId = $request->input("id");
        $pet = Pet::find($petId);

        //TODO: check if the auth user matches the owner ID.

        Log::debug($pet);

        // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $pet->update($request->validated()->except("id"));

        //TODO: add success
        return response()->json(new PetResource($pet),200);
    }

    public function getDetail ($id) {

        // find pet by id
        $pet = Pet::find($id);

        //TODO: check if the auth user matches the owner ID.


        if($pet === null) {
            return response()->json(["error"=>"Pet not found"],404);
        }

        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
        return response()->json(new PetResource($pet),200);
    }

    // will need to put this, will need user id. The user ID can be derived from this list
    public function getList (Request $request) {
        $id = Auth::id();
        //TODO: will need to add linked pets if id is linked to another account to view pet data. kani, I still have to think how to write this logic..

        // will all picture here in the near future...
        $pets = Pet::select('id'
                , 'name'
                , 'breed'
                ,'animal_type'
                ,'image_storage_path as pet_image')
            ->where('pet_owner_id',$id)
            ->get();

        $pets = $pets->map(function ($item) {
            $pathToFile = $item->pet_image;
            $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addHour(1)) : null;

            $item->pet_image=$temporaryUrl;
            return $item;
        });

        return response()->json(
            [
            "pets_owned"=>$pets,
            "linked_pets"=>[]
        ],200);
    }

    public function changeAvatar (Request $request) {
        // get pet id
        $id = $request->input('pet_id');

        // get logged user
        $authUserId = Auth::id();

        // check if user has access
        // find pet by id
        $pet = Pet::find($id);

        if ($authUserId !== $pet->pet_owner_id) {
            return response()->json(["error"=>"You are not authorized"],403);
        }

        // delete existing file
        if($pet->image_storage_path !== null) {
            Storage::delete($pet->image_storage_path);
        }

        // get file
        $imageFile = $request->file('image');
        $imageName = $imageFile->hashName();

        $directory = $authUserId;
        Log::debug($imageFile);
        Storage::disk('local')->putFileAs($directory, $imageFile,$imageName);

        $pathToFile = $directory."/".$imageName;
        $pet->update(
            ["image_storage_path"=>$pathToFile]
        );

        $url= Storage::temporaryUrl($pathToFile,now()->addHour(1));

        return response()->json([
            'message' => "Image updated successfully",
            'image_url' => $url,
        ]);

    }
    // TODO: add pet picture route
    // TODO: add picture to return json

}

