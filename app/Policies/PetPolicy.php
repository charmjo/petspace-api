<?php

namespace App\Policies;

use App\Models\Pet\Pet;
use App\Models\User;

class PetPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view (User $user, Pet $pet)
    {

    }
}
