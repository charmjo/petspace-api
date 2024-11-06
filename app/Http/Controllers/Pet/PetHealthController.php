<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\Pet\Allergen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetHealthController extends Controller
{
    public function getAllergenDictionary () : JsonResponse {
        $allergyList = Allergen::all();
        return response()->json([
            "message" => "Allergy List retrieved successfully",
            "list" => $allergyList
        ],200);
    }

    public function addPetAllergen (Request $request) : JsonResponse {
        $allergyList = Allergen::all();
        return response()->json([
            "message" => "Allergy List retrieved successfully",
            "list" => $allergyList
        ],200);
        // TODO: add pet allergen
        // get the pet and the allergen id
        // check if user already added allergen
        // add it to db
        // return list of allergen
    }
    public function removePetAllergen ($id) : JsonResponse {
        // TODO: remove pet allergen
        $allergyList = Allergen::all();
        return response()->json([
            "message" => "Allergy List retrieved successfully",
            "list" => $allergyList
        ],200);

        // get the pet allergy id
        // remove the allergen
        // return list of allergen
    }

}
