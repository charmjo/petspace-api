<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    const MEMBER_LIMIT = 10;

    public function addMember (Request $request) {
        // TODO: transfer to user model, this is so unholy

        // get authenticated userID
        $id = Auth::id();

        // check if user already added 10 members
        $memberCount = DB::table('user_family')
            ->where('main_user_id',$id)
            ->count();

        if($memberCount >= self::MEMBER_LIMIT) {
            return response()->json(['message' => 'Member limit already reached.'], 403);
        }

        // get and validate email
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please provide a valid email address.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // TODO: restrict count to 10 items.

        // find email id, I didn't use pluck because I want it to be as near to the raw SQL but with protections
        $memberId = User::select('id')
            ->where('email', $validator->validated()['email'])
            ->first();

        if ($memberId === null) {
            // add return message if email is not found
            return response()->json(['message' => 'User not found'], 404);
        }

        // TODO: add constraint on the database table and move the checking logic to the exception
        // get existing data
        $existingData = DB::table('user_family')
            ->where('main_user_id',$id)
            ->where('family_member_id', $memberId->id)
            ->get();

        // check if family member is already added
        if ($existingData->containsOneItem()) {
            return response()->json(['message' => 'Family member already exists'], 409);
        }

        // Data to be inserted
        $data = [
            'main_user_id' => $id,
            'family_member_id' => $memberId->id,
        ];

        // Inserting data into the 'users' table
        try {
            // Attempt to insert the data into the database
            DB::table('user_family')->insert($data);

            // will return the list of the updated data. I will only do transactions if there are multiple inserts
            $results = User::getAllFamilyMembers($id);

            return response()->json([
                'message'=>'Family member added successfully.',
                'list'=> $results
            ],200);
        } catch (QueryException $e) {
            // Handle the error if the insert fails
            return response()->json(['message' => 'Failed to add family member.'], 500);
        }

        // TODO: notify member
        // TODO: member list
    }

    public function removeMember ($toDeleteId) : JsonResponse {
        $userToDelete= DB::table('user_family')
            ->where('id', $toDeleteId)
            ->first();

        if ($userToDelete === null) {
            // add return message if email is not found
            return response()->json(['message' => 'User not found'], 404);
        }

        // get authenticated user
        $authUserId = Auth::id();

        // compare if authenticated user is parent id.
        if ($authUserId !== $userToDelete->main_user_id) {
            return response()->json(['message' => 'Not allowed'], 401);
        }

        try {
            DB::table('user_family')->where('id', $toDeleteId)->delete();

            // will return the list of the updated data. I will only do transactions if there are multiple inserts
            $results = User::getAllFamilyMembers($authUserId);
            return response()->json([
                'message'=>'Family member removed successfully.',
                'list'=>$results
            ],200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Family member could not be deleted.'], 500);
        }
        //TODO: notify removed member
        //TODO: return member list
    }

    public function getAllMembers () : JsonResponse {
        // get authenticated user
        $authUserId = Auth::id();

        // return all added members
        $results = User::getAllFamilyMembers($authUserId);

        return response()->json([ "list"=>$results],200);
    }

}
