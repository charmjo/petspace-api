<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
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
