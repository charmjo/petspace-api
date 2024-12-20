<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pet\CreateNewPetRequest;
use App\Http\Resources\Pet\PetResource;
use App\Models\Pet\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function create (CreateNewPetRequest $request) {
        Log::debug('create pet request here: ');
        Log::debug($request);

        $petOwnerId = $request->user()->id;
        $authUserId = Auth::id();

        $petData = array_merge($request->validated(), ['pet_owner_id' => $petOwnerId]);


        // if image key exist
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = $imageFile->hashName();

            $directory = "{$authUserId}/images";
            Log::debug($imageFile);
            Storage::disk('local')->putFileAs($directory, $imageFile,$imageName);

            $pathToFile = $directory."/".$imageName;
            $petData = array_merge($petData,["image_storage_path"=>$pathToFile]);
        }

        Pet::create($petData);
        return response()->json(["message"=>"Pet added successfully"],201);
    }

    public function delete ($id) {
        Log::debug('delete pet here');

        $pet = Pet::find($id);

        $authId = Auth::id();
        //TODO: check if the auth user matches the owner ID.
        $mainMembers = User::getFamilyMembers($pet->pet_owner_id);
        if ($authId !== $pet->pet_owner_id && !in_array($authId,$mainMembers)) {
            return response()->json(['message' => 'Not found'], 404);
        }

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
        $authId = Auth::id();
        $mainMembers = User::getFamilyMembers($pet->pet_owner_id);
        if ($authId !== $pet->pet_owner_id && !in_array($authId,$mainMembers)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $petData = $request->validated();

        // if image key exist
        if ($request->hasFile('image')) {
            if($pet->image_storage_path !== null) {
                Storage::delete($pet->image_storage_path);
            }

            $imageFile = $request->file('image');
            $imageName = $imageFile->hashName();

            $directory = "{$pet->pet_owner_id}/images";
            Storage::disk('local')->putFileAs($directory, $imageFile,$imageName);
            $pathToFile = $directory."/".$imageName;
            $petData = array_merge($petData,["image_storage_path"=>$pathToFile]);
        }


        $pet->update($petData);

        $updatedPet = Pet::find($petId);

        //TODO: add success
        return response()->json(new PetResource($updatedPet),200);
    }

    /**
     * @OA\Get(
     *     path="/pet/pet-detail/{petId}",
     *     summary="Get pet details by ID",
     *     description="Retrieves detailed information about a pet, including its breed, gender, color, and other details.",
     *     operationId="getPetDetail",
     *     tags={"Pet Profile"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Nacho"),
     *             @OA\Property(property="breed", type="string", example="Persian"),
     *             @OA\Property(property="dob", type="string", format="date", example="2022/06/28"),
     *             @OA\Property(property="gender", type="string", example="male"),
     *             @OA\Property(property="animal_type", type="string", example="cat"),
     *             @OA\Property(property="color", type="string", example="white"),
     *             @OA\Property(property="bio", type="string", nullable=true, example=null),
     *             @OA\Property(property="is_microchipped", type="boolean", nullable=true, example=null),
     *             @OA\Property(property="microchip_id", type="string", nullable=true, example=null),
     *             @OA\Property(property="is_spayed_neutered", type="boolean", example=true),
     *             @OA\Property(property="pet_image", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */

    public function getDetail ($id) {

        // find pet by id
        $pet = Pet::find($id);
        $authId = Auth::id();

        //TODO: check if the auth user matches the owner ID.
        $mainMembers = User::getFamilyMembers($pet->pet_owner_id);
        if ($authId !== $pet->pet_owner_id && !in_array($authId,$mainMembers)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if($pet === null) {
            return response()->json(["message"=>"Pet not found"],404);
        }

        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
        return response()->json(new PetResource($pet),200);
    }

    // will need to put this, will need user id. The user ID can be derived from this list
    public function getList (Request $request) {
        $authId = Auth::id();


        $pets = Pet::select('id'
            , 'name'
            , 'breed'
            ,'animal_type'
            ,'dob'
            ,'image_storage_path as pet_image')
            ->where('pet_owner_id',$authId)
            ->get();

        // get linked pets, this is for many-to-many relationships
        $mainUserIds = User::getMainFamilyMembers(Auth::id());

        $linkedPets = Pet::select('id'
            , 'name'
            , 'breed'
            ,'animal_type'
            ,'dob'
            ,'image_storage_path as pet_image')
            ->whereIn('pet_owner_id',$mainUserIds)
            ->get();

        // format retrieved data pets
        $pets = $pets->map(function ($item) {

            $pathToFile = $item->pet_image;
            $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addHour(1)) : null;

            $item->animal_type = ucfirst(strtolower($item->animal_type));
            $item->pet_image=$temporaryUrl;
            return $item;
        });

        // format retrieved data linked pets
        $linkedPets = $linkedPets->map(function ($item) {

            $pathToFile = $item->pet_image;
            $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addHour(1)) : null;

            $item->animal_type = ucfirst(strtolower($item->animal_type));
            $item->pet_image=$temporaryUrl;
            return $item;
        });

        return response()->json(
            [
                "pets_owned"=>$pets,
                "linked_pets"=>$linkedPets
            ],200);
    }

    public function changeAvatar (Request $request) {
        // get pet id
        $id = $request->input('pet_id');

        // get logged user
        $authId = Auth::id();

        // TODO: place this somewhere to centralize this checking code
        // check if user has access
        // find pet by id
        $pet = Pet::find($id);

        $mainMembers = User::getFamilyMembers($pet->pet_owner_id);
        if ($authId !== $pet->pet_owner_id && !in_array($authId,$mainMembers)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // delete existing file
        if($pet->image_storage_path !== null) {
            Storage::delete($pet->image_storage_path);
        }

        // get file
        $imageFile = $request->file('image');
        $imageName = $imageFile->hashName();

        $directory = "{$pet->pet_owner_id}/images";
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

