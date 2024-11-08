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

    /**
     * @OA\Get(
     *     path="/account/user",
     *     summary="Get user account details",
     *     description="Retrieves the account details of the currently authenticated user. Web register and login returns the same json structure",
     *     operationId="getUser",
     *     tags={"Account"},
     *     @OA\Response(
     *         response=200,
     *         description="User account details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=2),
     *             @OA\Property(property="first_name", type="string", example="Charm"),
     *             @OA\Property(property="last_name", type="string", example="Test"),
     *             @OA\Property(property="role", type="string", nullable=true, example=null),
     *             @OA\Property(property="dob", type="string", format="date", nullable=true, example=null),
     *             @OA\Property(property="gender", type="string", nullable=true, example=null),
     *             @OA\Property(property="email", type="string", format="email", example="test2@example.com"),
     *             @OA\Property(property="phone", type="string", nullable=true, example=null),
     *             @OA\Property(property="pets_count", type="integer", example=3),
     *             @OA\Property(property="is_form_filled", type="boolean", nullable=true, example=null),
     *             @OA\Property(property="profile_image", type="string", format="url", nullable=true, example=null),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="street_name", type="string", nullable=true, example=null),
     *                 @OA\Property(property="city", type="string", nullable=true, example=null),
     *                 @OA\Property(property="province", type="string", nullable=true, example=null),
     *                 @OA\Property(property="postal_code", type="string", nullable=true, example=null),
     *                 @OA\Property(property="country", type="string", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User account not found"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/account/update",
     *     summary="Update user account details",
     *     description="Updates the user account information, including address and personal details",
     *     operationId="updateUserAccount",
     *     tags={"Account"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="first_name", type="string", maxLength=255, example="Steve", description="User's first name"),
     *                 @OA\Property(property="last_name", type="string", maxLength=255, example="Tester", description="User's last name"),
     *                 @OA\Property(property="phone", type="string", example="(123) 456-7890", description="User's phone number"),
     *                 @OA\Property(property="address_street_name", type="string", maxLength=255, example="103 Redfox Grove", description="Street name of the user's address"),
     *                 @OA\Property(property="address_city", type="string", maxLength=255, example="Waterloo", description="City of the user's address"),
     *                 @OA\Property(property="address_province", type="string", maxLength=255, example="Alberta", description="Province of the user's address"),
     *                 @OA\Property(property="address_country", type="string", maxLength=255, example="Canada", description="Country of the user's address"),
     *                 @OA\Property(property="address_postal_code", type="string", maxLength=255, example="A1B 2C3", description="Postal code of the user's address"),
     *                 @OA\Property(property="is_form_filled", type="boolean", example=true, description="Indicates whether the user form has been filled")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User account updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=3),
     *             @OA\Property(property="first_name", type="string", example="Steve"),
     *             @OA\Property(property="last_name", type="string", example="Tester"),
     *             @OA\Property(property="role", type="string", nullable=true, example=null),
     *             @OA\Property(property="dob", type="string", format="date", nullable=true, example=null),
     *             @OA\Property(property="gender", type="string", nullable=true, example=null),
     *             @OA\Property(property="email", type="string", format="email", example="steve.test@example.com"),
     *             @OA\Property(property="phone", type="string", example="(123) 456-7890"),
     *             @OA\Property(property="pets_count", type="integer", example=4),
     *             @OA\Property(property="is_form_filled", type="boolean", nullable=true, example=null),
     *             @OA\Property(property="profile_image", type="string", format="url", nullable=true, example=null),
     *             @OA\Property(
     *                 property="address",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=3),
     *                 @OA\Property(property="street_name", type="string", example="103 Redfox Grove"),
     *                 @OA\Property(property="city", type="string", example="Waterloo"),
     *                 @OA\Property(property="province", type="string", example="Alberta"),
     *                 @OA\Property(property="postal_code", type="string", example="A1B 2C3"),
     *                 @OA\Property(property="country", type="string", example="Canada")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found - User not found"
     *     )
     * )
     */
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
