<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Account\UpdateUserRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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

    public function updateUser (UpdateUserRequest $request, $id) {
        // find user by id
        $user = User::find($id);

        // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $data = $request->except('password','id');

        try {
            $user->update($data);

            $userDetail = User::find($id);
            return response()->json([
                    'data' => $userDetail
                ],200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to update data.'], 500);
        }
        // will need some type of return message for this. i'd rather not make a response action since it sounds overkill.
    }

    public function addMember (Request $request) {
        // TODO: transfer to user model, this is so unholy

        // get authenticated userID
        $id = Auth::id();

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

        // find email id, I didn't use pluck because I want it to be as near to the raw SQL but with protections
        $memberId = User::select('id')
            ->where('email', $validator->validated()['email'])
            ->first();
    
        if ($memberId === null) {
            
            // add return message if email is not found
            return response()->json(['message' => 'User not found'], 404);
        }

        // add member if found 
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
                'data'=>$results
            ],200);
        } catch (QueryException $e) {
            // Handle the error if the insert fails
            return response()->json(['message' => 'Failed to add family member.'], 500);
        }

        // TODO: notify member 
        // TODO: member list
    }

    public function removeMember ($toDeleteId) {
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
                'data'=>$results
            ],200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Could not delete user.'], 500);
        }
        //TODO: notify removed member
        //TODO: return member list
    }

    // TODO:transfer to model
    public function getAllMembers () {
        // get authenticated user
        $authUserId = Auth::id();

        // return all added members
        $results = User::getAllFamilyMembers($authUserId);

        return response()->json(['data'=>$results],200);
    }

}
