<?php
namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Pet\PetDocuRecordResource;
use App\Models\Pet\Pet;
use App\Models\Pet\PetDocuRecords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetDocuRecordsController extends Controller
{



    /**
     * @OA\Post(
     *     path="/pet-record/upload",
     *     summary="Upload a pet document",
     *     tags={"Pet Document Records"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="pet_id", type="integer", example=1),
     *             @OA\Property(property="filename", type="string", example="document.pdf"),
     *             @OA\Property(property="document", type="string", format="binary", example="document.pdf"),
     *             @OA\Property(property="date_added", type="string", format="date", example="2024-11-04")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function create (Request $request) : JsonResponse {

        // TODO : validation
        // get pet id
        $petId = $request->input('pet_id');

        // get logged user
        $authUserId = Auth::id();

        // check if user has access
        // find pet by id
        $pet = Pet::find($petId);

        if ($authUserId !== $pet->pet_owner_id) {
            return response()->json(["error"=>"You are not authorized"],403);
        }

        // get file
        $file = $request->file('document');
        $fileName = $file->hashName();

        $directory = "{$authUserId}/documents";
        Storage::disk('local')->putFileAs($directory, $file,$fileName);

        $pathToFile = $directory."/".$fileName;

        // save records to db
        //
        $record = new PetDocuRecords();
        $record->pet_id = $petId;
        $record->added_by = $authUserId;
        $record->record_path = $pathToFile;
        $record->filename = $request->input("filename");
        $record->date_added = $request->input("date_added");
        $record->setCreatedAt(now());
        $record->setUpdatedAt(now());
        $record->save();

        $url= Storage::temporaryUrl($pathToFile,now()->addHour(1));

        return response()->json([
            'message' => "Document uploaded successfully",
            'document_url' => $url,
        ],201);

    }

    public function delete ($id) : void {
        // TODO: DELETE
    }

    /**
     * @OA\Get(
     *     path="/pet-record/list/{pet_id}",
     *     summary="Retrieve document records for a specific pet",
     *     tags={"Pet Document Records"},
     *     @OA\Parameter(
     *          name="pet_id",
     *          in="path",
     *          required=true,
     *          description="ID of the pet to retrieve records for",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Record retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Record retrieved successfully"),
     *             @OA\Property(
     *                 property="list",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="pet_id", type="integer", example=1),
     *                     @OA\Property(property="added_by_id", type="integer", example=3),
     *                     @OA\Property(property="added_by_first_name", type="string", example="Steve"),
     *                     @OA\Property(property="added_by_last_name", type="string", example="Tester"),
     *                     @OA\Property(
     *                         property="record_path",
     *                         type="string",
     *                         format="url",
     *                         example="http://localhost:8000/storage/3/documents/kXD9Eh3xZBTEXjwzvIIZBcqY6TJGG2BN07PlU34F.pdf?expires=1730855033&signature=c3e5c7daa5a259afc22b91580bc3d8a88b4b7c4749a206bf3e7ade0731ef7b59"
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-04T20:21:36.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-04T20:21:36.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You are not authorized"
     *     ),
     *      @OA\Response(
     *          response=404,
     *          description="No documents found"
     *      )
     * )
     */
    // send a pet id in the request
    public function getList ($id) : JsonResponse {
        // TODO: validation
        $petId = $id;

        // Check if authorized
        // get logged user
        $authUserId = Auth::id();
        $pet = Pet::find($petId);

        // check if user has access
        // TODO: change this when pet links are added
        if ($authUserId !== $pet->pet_owner_id) {
            return response()->json(["error"=>"You are not authorized"],403);
        }

        // get all records associated to the pet
        $pet = Pet::with(['docuRecords' => function ($query) {
            $query->leftJoin('users', 'users.id', '=', 'pet_docu_records.added_by')
                ->addSelect('pet_docu_records.*','users.first_name', 'users.last_name');
        }])
            ->find($petId);

        // check if null
        if($pet->docuRecords === null) {
            return response()->json(["error"=>"No documents found"],404);
        }
        // assign pet records to records
        $records = $pet->docuRecords;

        $recordsCollection = PetDocuRecordResource::collection($records);

        return response()->json([
            "message" => "Record retrieved successfully",
            "list" => $recordsCollection],200);
    }

}
