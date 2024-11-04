<?php

namespace App\Http\Controllers\Pet;

use App\Http\Controllers\Controller;
use App\Models\Pet\Pet;
use App\Models\Pet\PetDocuRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PetDocuRecordsController extends Controller
{
    public function create (Request $request) {


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
        Log::debug($file);
        Storage::disk('local')->putFileAs($directory, $file,$fileName);

        $pathToFile = $directory."/".$fileName;

        // save records to db
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

}
