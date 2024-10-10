<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Account\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class UserController extends Controller
{
    // I want to perform soft deletion just to keep the data for stats or to prevent doing something irreversible at the expense of the db.
    public function deleteUser ($id) {
        Log::debug('delete user here');

        $user = User::find($id);

        // step 2: revoke keys and sessions. I still do not know how to delete sessions.
        $user->tokens()->delete();

        // delete the user,
        $user->delete();

        // return response of deleted user, frontend will deal with frontend things.
        Log::debug('user deleted');
    }

    public function updateUser (UpdateUserRequest $request, $id) {
        Log::debug('update user here');

        // find user by id
        $user = User::find($id);

        Log::debug($user);

        // exclude some fields as I want another function to handle password change
        // the request action holds validation so this should be okay.
        $data = $request->except('password','id');
        $user->update($data);

        // will need some type of return message for this. i'd rather not make a response since it sounds overkill.
    }

}
