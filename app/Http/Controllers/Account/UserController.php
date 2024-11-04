<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateUserRequest;
use App\Http\Resources\Account\UserResource;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    const MEMBER_LIMIT = 10;
    public function  getUser (Request $request): \Illuminate\Http\JsonResponse
    {
        // TODO: place this in a controller this is so unholy.
        $authUserId = Auth::id();
        $userDetail = User::with('address')
                            -> withCount('pets')
                            ->findOrFail($authUserId);

        return response()->json(new UserResource($userDetail),
        200);
    }
    // I want to perform soft deletion just to keep the data for stats or to prevent doing something irreversible at the expense of the db.
    public function deleteUser ($id) {
        // TODO: add authenticated user check
        $user = User::find($id);

        // step 2: revoke keys and sessions. I still do not know how to delete sessions.
        $user->tokens()->delete();

        // delete the user,
        $user->delete();

        // return response of deleted user, frontend will deal with frontend things.
    }

   public function updateUser (UpdateUserRequest $request) : JsonResponse {
        // find user by id
        $authUserId = Auth::id();
        $user = User::find($authUserId);

       // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $data = $request->except('id','password','email');

        $userData = [
            "first_name" => data_get($data,'first_name'),
            "last_name" => data_get($data,'last_name'),
            "phone" => data_get($data,'phone'),
        ];

        $addressData = [
            "street_name" => data_get($data,'address_street_name'),
            "postal_code" => data_get($data,'address_postal_code'),
            "country" => data_get($data,'address_country'),
            "province" => data_get($data,'address_province'),
            "city" => data_get($data,'address_city'),
        ];

        $request->input('address');

        try {
            $user->update($userData);
            $user->address()->updateOrCreate(
                ['user_id' => $user->id],
                $addressData
            );

            $userDetail = User::with('address')
                ->withCount('pets')
                ->findOrFail($authUserId);

            return response()->json(new UserResource($userDetail),
                200);
        } catch (QueryException $e) {
            Log::debug($e);
            return response()->json(['message' => 'Failed to update data.'], 500);
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }

    public function changeAvatar (Request $request) : JsonResponse {
        // get user
        $authUserId = Auth::id();
        $user = User::find($authUserId);

        // delete existing file
        if($user->avatar_storage_path !== null) {
            Storage::delete($user->avatar_storage_path);
        }

        // get file
        $imageFile = $request->file('image');
        $imageName = $imageFile->hashName();

        $directory = "{$authUserId}/images";
        Log::debug($imageFile);
        Storage::disk('local')->putFileAs($directory, $imageFile,$imageName);

        $pathToFile = $directory."/".$imageName;
        $user->update(
            ["avatar_storage_path"=>$pathToFile]
        );

        $url= Storage::temporaryUrl($pathToFile,now()->addHour(1));


        return response()->json([
            'message' => "Image updated successfully",
            'image_url' => $url,
        ]);

    }

}
