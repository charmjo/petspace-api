<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\Pet\Allergen;
use App\Models\Pet\Pet;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetHealthController extends Controller
{
    public function getAllergenDictionary () : JsonResponse {
        $allergyList = Allergen::all();
        return response()->json([
            "message" => "Allergy List retrieved successfully",
            "list" => $allergyList
        ],200);
    }

    public function addPetAllergen ($petId,$allergenId) : JsonResponse {
        
        //TODO: validate PARAMETERS


        // get the pet and the allergen id
        $loggedInUserId = Auth::id();
        

        // TODO: authorization of pet_owner and vet
        $data = [
            'pet_id' => $petId,
            'allergen_id' => $allergenId,
            'added_by' => $loggedInUserId,
            'created_date' => now()
        ];

        try {
            DB::table('pet_allergy_record')->insert($data);
        } catch (QueryException $e) {

            // Unique or foreign key constraint violation for added allergen.
            // I am doing this to minimize queries.
            // unique key constraint violation is the most probable error that I will be facing as I will format the input and the validation.
            if ($e->getCode() == 23000) {
                return response()->json(['error' => 'Allergy record already exists'],409);
            }
        }
        
        $petAllergyList = Pet::retrievePetAllergenList($petId);
        // I added added_by_role and the reason for that is the allergy could be added by a doctor or not.
        //TODO: add list to history table (this is soooo going to be bloated with rows)


        return response()->json([
            "message" => "Pet Allergy added successfully",
            "list" => $petAllergyList
        ],201);

    }

    // allergy id
    public function removePetAllergen ($petId,$petAllergenId) : JsonResponse {
        // TODO: validate parameters
        //

        $authUserId = Auth::id();

                // TODO: place this somewhere to centralize this checking code
        // find pet by id
        $pet = Pet::find($petId);


        if ($authUserId !== $pet->pet_owner_id) {
            return response()->json(["error"=>"You are not authorized"],403);
        }
        // delete pet allergen
        DB::table('pet_allergy_record')
            ->where('allergen_Id', $petAllergenId)
            ->where('pet_Id', $petId)->delete();
        // get the pet allergy id
        // remove the allergen
        // return list of allergen

         //TODO: add list to history table (this is soooo going to be bloated with rows)
        $petAllergyList = Pet::retrievePetAllergenList($petId);
        return response()->json([
            "message" => "Pet allergy removed successfuly",
            "list" => $petAllergyList
        ],200);
    }

    // use pet Id here
    public function getPetAllergenList ($id) : JsonResponse {
        $petId = $id;

        $petAllergyList = Pet::retrievePetAllergenList($petId);
        // I added added_by_role and the reason for that is the allergy could be added by a doctor or not.
        
        return response()->json([
            "message" => "Pet allergy list retrieved successfully",
            "list" => $petAllergyList
        ],200);


    }

    // 
    public function updateWeight($petId,$weight) {
       // TODO: validate parameters
        // add the the weight 

    }

    public function getLatestWeight($petId) {
        // TODO: validate parameters
         // add the the weight 
         
    }

    public function getWeightHistory($petId) {
        // TODO: validate parameters
         // add the the weight 
         
    }

}
