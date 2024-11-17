<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\Pet\Allergen;
use App\Models\Pet\Pet;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetHealthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pet/allergen-dictionary",
     *     summary="Get allergen dictionary list",
     *     description="Retrieves a list of allergens and their classifications",
     *     operationId="getAllergenDictionary",
     *     tags={"PetHealth"},
     *     @OA\Response(
     *         response=200,
     *         description="Allergy List retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Allergy List retrieved successfully"),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1, description="ID of the allergen"),
     *                     @OA\Property(property="allergen", type="string", example="Beef", description="Name of the allergen"),
     *                     @OA\Property(property="classification", type="string", example="Food", description="Classification of the allergen"),
     *                     @OA\Property(property="species_affected", type="string", example="Dog & Cat", description="Species affected by the allergen"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example=null, description="Timestamp of allergen creation"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null, description="Timestamp of allergen update")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Allergens not found"
     *     )
     * )
     */
    public function getAllergenDictionary () : JsonResponse {
        $allergyList = Allergen::all();
        return response()->json([
            "message" => "Allergy List retrieved successfully",
            "list" => $allergyList
        ],200);
    }

    /**
     * @OA\Post(
     *      path="pet/{petId}/allergy/add/{allergenId}",
     *      summary="Add a pet allergy",
     *      description="Adds an allergy to a pet and returns the updated list of allergies",
     *      operationId="addPetAllergy",
     *      tags={"PetHealth"},
     *      @OA\Parameter(
     *          name="petId",
     *          in="path",
     *          description="ID of the pet",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="allergenId",
     *          in="path",
     *          description="ID of the allergen",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Allergy added successfully with updated allergy list",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Pet Allergy added successfully"),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2, description="Record ID of the pet allergy"),
     *                     @OA\Property(property="allergen_id", type="integer", example=11, description="ID of the allergen"),
     *                     @OA\Property(property="allergen", type="string", example="Turkey", description="Name of the allergen"),
     *                     @OA\Property(property="classification", type="string", example="Food", description="Classification of the allergen"),
     *                     @OA\Property(property="added_by_first_name", type="string", example="Steve", description="First name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_last_name", type="string", example="Tester", description="Last name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_role", type="string", nullable=true, example=null, description="Role of the person who added the allergy, if applicable")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */
    public function addPetAllergen (int $petId, int $allergenId) : JsonResponse {

        //TODO: validate PARAMETERS
        // get the pet and the allergen id
        $loggedInUserId = Auth::id();


        // TODO: authorization of pet_owner and vet
        $data = [
            'pet_id' => $petId,
            'allergen_id' => $allergenId,
            'added_by' => $loggedInUserId,
            'created_at' => now()
        ];

        try {
            //TODO: I can just make a view out of this. Why haven't I thought of this before???
            // Ideally I would like to put the main record and the history into a transaction.
            DB::table('pet_allergy_record')->insert($data);

            // TODO: PUT THIS IN A MODEL HUHUHUHU
            DB::table('pet_allergy_history')->insertUsing(
                ['allergen_id', 'pet_id', 'added_by','created_at'],
                DB::table('pet_allergy_record') // source table
                    ->select('allergen_id', 'pet_id','added_by'
                        , DB::raw("'".now()."' as created_at"))  // I do not like raw but this is a necessary evil until I figure out how to implement views
                    ->where('pet_id', $petId)
                    ->where('added_by', $loggedInUserId)// Add condition if needed
            );
        } catch (QueryException $e) {
            Log::debug($e->getMessage());
            // Unique or foreign key constraint violation for added allergen.
            // I am doing this to minimize queries.
            // unique key constraint violation is the most probable error that I will be facing as I will format the input and the validation.
            if ($e->getCode() == 23000) {
                return response()->json(['error' => 'Allergy record already exists'],409);
            }
        }

        $petAllergyList = Pet::retrievePetAllergen($petId,true);
        // I added added_by_role and the reason for that is the allergy could be added by a doctor or not.
        //TODO: add list to history table (this is soooo going to be bloated with rows)

        return response()->json([
            "message" => "Pet Allergy added successfully",
            "list" => $petAllergyList
        ],201);
    }

    /**
     * @OA\Delete(
     *     path="/pet/{petId}/allergy/remove/{allergenId}",
     *     summary="Remove pet allergy",
     *     description="Removes an allergy from the specified pet and returns the updated allergy list",
     *     operationId="removePetAllergy",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="allergenId",
     *         in="path",
     *         description="ID of the allergen to be removed",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet allergy removed successfully with updated allergy list",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Pet allergy removed successfully"),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2, description="Record ID of the pet allergy"),
     *                     @OA\Property(property="allergen_id", type="integer", example=11, description="ID of the allergen"),
     *                     @OA\Property(property="allergen", type="string", example="Turkey", description="Name of the allergen"),
     *                     @OA\Property(property="classification", type="string", example="Food", description="Classification of the allergen"),
     *                     @OA\Property(property="added_by_first_name", type="string", example="Steve", description="First name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_last_name", type="string", example="Tester", description="Last name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_role", type="string", nullable=true, example=null, description="Role of the person who added the allergy, if applicable")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet or allergen not found"
     *     )
     * )
     */
    public function removePetAllergen (int $petId, int $petAllergenId) : JsonResponse {
        // TODO: validate parameters


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

        // TODO: PUT THIS IN A MODEL HUHUHUHU
        DB::table('pet_allergy_history')->insertUsing(
            ['allergen_id', 'pet_id', 'added_by','created_at'],
            DB::table('pet_allergy_record') // source table
            ->select('allergen_id', 'pet_id','added_by'
                , DB::raw("'".now()."' as created_at"))  // I do not like raw but this is a necessary evil until I figure out how to implement views
            ->where('pet_id', $petId)
                ->where('added_by', $authUserId)// Add condition if needed
        );
        // get the pet allergy id
        // remove the allergen
        // return list of allergen

         //TODO: add list to history table (this is soooo going to be bloated with rows)
        $petAllergyList = Pet::retrievePetAllergen($petId,true);
        return response()->json([
            "message" => "Pet allergy removed successfuly",
            "list" => $petAllergyList
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/pet/{petId}/allergies",
     *     summary="Get pet allergy list",
     *     description="Retrieves a list of allergies for the specified pet",
     *     operationId="getPetAllergies",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet allergy list retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Pet allergy list retrieved successfully"),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2, description="Record ID of the pet allergy"),
     *                     @OA\Property(property="allergen_id", type="integer", example=11, description="ID of the allergen"),
     *                     @OA\Property(property="allergen", type="string", example="Turkey", description="Name of the allergen"),
     *                     @OA\Property(property="classification", type="string", example="Food", description="Classification of the allergen"),
     *                     @OA\Property(property="added_by_first_name", type="string", example="Steve", description="First name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_last_name", type="string", example="Tester", description="Last name of the person who added the allergy"),
     *                     @OA\Property(property="added_by_role", type="string", nullable=true, example=null, description="Role of the person who added the allergy, if applicable")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */
    public function getPetAllergenList (int $id) : JsonResponse {
        $petId = $id;

        $petAllergyList = Pet::retrievePetAllergen($petId,true);
        // I added added_by_role and the reason for that is the allergy could be added by a doctor or not.


        return response()->json([
            "message" => "Pet allergy list retrieved successfully",
            "list" => $petAllergyList
        ],200);


    }

    /**
     * @OA\Post(
     *     path="pet/{petId}/weight/update/{weight}",
     *     summary="Updates pet weight",
     *     description="Updates pet weight",
     *     operationId="updatePetWeight",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *          name="weight",
     *          in="path",
     *          description="pet weight",
     *          required=true,
     *          @OA\Schema(type="decimal")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of weight records",
     *         @OA\JsonContent(
     *              type="object",
     *             @OA\Property(property="message", type="string", example="Pet weight updated successfully"),
     *             @OA\Property(
     *                  property="weight",
     *                  type="object",
     *                  @OA\Property(property="weight", type="number", format="float", example=5.5, description="Updated weight of the pet"),
     *                  @OA\Property(property="added_by_first_name", type="string", example="Steve", description="First name of the person who updated the weight"),
     *                  @OA\Property(property="added_by_last_name", type="string", example="Tester", description="Last name of the person who updated the weight")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */
    public function updateWeight(int $petId, int $weight) : JsonResponse {
       // TODO: validate parameters

        // add the weight
        $authId = Auth::id();
        $data = [
            'pet_id' => $petId,
            'added_by' => $authId,
            'weight' => $weight,
            'created_at' => now()
        ];
        DB::table('pet_weight_record')->insert($data);

        //get latest weight
        $latestWeight = Pet::retrieveWeight($petId, false);

        return response()->json([
            "message" => "Pet weight updated successfully",
            "weight" => $latestWeight
        ],201);
    }

    /**
     * @OA\Get(
     *     path="/pet/{petId}/weight/latest",
     *     summary="Retrieve pet's latest weight",
     *     description="Returns data for pet's latest weight and the user who added it.",
     *     operationId="getPetWeightLatest",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pet's latest weight record retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(
     *                 property="weight",
     *                 description="Pet weight in kilograms",
     *                 type="number",
     *                 format="decimal",
     *                 example="3.5"
     *             ),
     *             @OA\Property(property="added_by_first_name", type="string", example="Steve"),
     *             @OA\Property(property="added_by_last_name", type="string", example="Tester"),
     *             @OA\Property(
     *                 property="created_at",
     *                 type="string",
     *                 format="date-time",
     *                 example="2024-11-07T21:52:28Z"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */
    public function getLatestWeight(int $petId) : JsonResponse {
        // TODO: validate parameters
         // add the the weight
        $result = Pet::retrieveWeight($petId);

        return response()->json(
            $result
        ,200);

    }


    public function getWeightHistory(int $petId) {
        // TODO: validate parameters
         // add the weight
        $result = Pet::retrieveWeight($petId,true);
        return $result !== null?
            response()->json([
                "message" => "Weight history retrieved successfully",
                "weight_history" => $result],200) :
            response()->json(["message" => "History not found"],404);
    }


    public function addSpecialCondition (Request $request, int $petId) : JsonResponse {
        // TODO: add validation
        $authId = Auth::id(); //TODO: can be doctor or pet_owner will have to discuss with Mariana
        $conditionName = $request->input('condition_name');
        $conditionNote = $request->input("condition_note", null);

        $data = [
            'pet_id' => $petId,
            'added_by' => $authId,
            'condition_name' => $conditionName,
            'condition_note' => $conditionNote,
            'created_at' => now()
        ];

        try {
            DB::table('pet_special_conditions_record')->insert($data);
        } catch (QueryException $e) {
            Log::debug($e);
            return response()->json(['message' => 'Failed to add special condition.'], 500);
        }
        return response()->json(['message' => 'Special condition added successfully.'], 201);
    }

    /**
     * @OA\Post(
     *     path="/pet/{petId}/special-cond/update/{conditionId}",
     *     summary="Update a special condition for a pet",
     *     description="Updates a specific special condition for a pet by condition ID.",
     *     operationId="updatePetSpecialCondition",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="conditionId",
     *         in="path",
     *         description="ID of the special condition",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=4
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Special condition details to be updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="condition_name", type="string", example="Not ticks"),
     *             @OA\Property(property="condition_note", type="string", example="More ticksss")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Special condition updated successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Special condition updated successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet or special condition not found"
     *     )
     * )
     */
    public function updateSpecialCondition (Request $request, int $petId, int $conditionId) : JsonResponse {
        // TODO: add validation

        $authId = Auth::id(); //TODO: can be doctor or pet_owner will have to discuss with Mariana
        $conditionName = $request->input('condition_name');
        $conditionNote = $request->input("condition_note",null);

        $data = [
            'condition_name' => $conditionNote,
            'condition_note' => $conditionName,
            'updated_at' => now()
        ];

        try {
            DB::table('pet_special_conditions_record')
                ->where('pet_id', $petId)
                ->where('id', $conditionId)
                ->update($data);
        } catch (QueryException $e) {
            Log::debug($e);
            return response()->json(['message' => 'Failed to add family member.'], 500);
        }
        return response()->json(['message' => 'Special condition updated successfully.'], 201);
    }

    /**
     * @OA\Delete(
     *     path="/pet/{petId}/special-cond/{conditionId}",
     *     summary="Delete a special condition for a pet",
     *     description="Deletes a specific special condition for a pet by its condition ID.",
     *     operationId="deletePetSpecialCondition",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="conditionId",
     *         in="path",
     *         description="ID of the special condition",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=3
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Special condition removed successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Special condition removed successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet or special condition not found"
     *     )
     * )
     */
    public function removeSpecialCondition (int $petId, int $conditionId) : JsonResponse {
        // TODO: add validation

        $authId = Auth::id(); //TODO: can be doctor or pet_owner will have to discuss with Mariana

        try {
            DB::table('pet_special_conditions_record')
                ->delete($conditionId);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to remove family member.'], 500);
        }
        return response()->json(['message' => 'Special condition removed successfully.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/pet/{petId}/special-cond/list",
     *     summary="Get special conditions for a pet",
     *     description="Retrieves a list of special conditions for a pet, including the condition name, note, and details of the user who added it.",
     *     operationId="getPetSpecialConditions",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Special condition retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Special condition retrieved successfully."
     *             ),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="pet_id", type="integer", example=2),
     *                     @OA\Property(property="added_by", type="integer", example=3),
     *                     @OA\Property(property="condition_name", type="string", example="Has a LOT ticks"),
     *                     @OA\Property(property="condition_note", type="string", example="Ticks"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-10 02:59:06"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null),
     *                     @OA\Property(property="added_by_first_name", type="string", example="Steve"),
     *                     @OA\Property(property="added_by_last_name", type="string", example="Tester")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet not found"
     *     )
     * )
     */
    public function getSpecialConditionList ($petId) : JsonResponse {
        // TODO: add validation

        $authId = Auth::id(); //TODO: can be doctor or pet_owner will have to discuss with Mariana

        $list = Pet::retrieveSpecialCond($petId, true);
        return response()->json([
            'message' => 'Special condition retrieved successfully.',
            'list' => $list
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/pet/{petId}/special-cond/latest",
     *     summary="Get the latest special condition for a pet",
     *     description="Retrieves the latest special condition for a pet, including the condition details and the user who added it.",
     *     operationId="getLatestPetSpecialCondition",
     *     tags={"PetHealth"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         description="ID of the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=2
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Special condition retrieved successfully.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Special condition retrieved successfully."
     *             ),
     *             @OA\Property(
     *                 property="condition",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="pet_id", type="integer", example=2),
     *                 @OA\Property(property="added_by", type="integer", example=3),
     *                 @OA\Property(property="condition_name", type="string", example="Has a LOT ticks"),
     *                 @OA\Property(property="condition_note", type="string", example="Ticks"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-10 02:59:06"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example=null),
     *                 @OA\Property(property="added_by_first_name", type="string", example="Steve"),
     *                 @OA\Property(property="added_by_last_name", type="string", example="Tester")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet or special condition not found"
     *     )
     * )
     */
    public function getSpecialConditionLatest ($petId) : JsonResponse {
        // TODO: add validation

        $authId = Auth::id(); //TODO: can be doctor or pet_owner will have to discuss with Mariana

        $condition = Pet::retrieveSpecialCond($petId, false);
        return response()->json([
            'message' => 'Special condition retrieved successfully.',
            'condition' => $condition
        ], 200);
    }



}
