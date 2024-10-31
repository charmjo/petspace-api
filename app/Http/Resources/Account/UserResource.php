<?php

namespace App\Http\Resources\Account;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $first_name
 * @property mixed $id
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone' => $this->phone,
            'pets_count' => $this->pets_count,
            'is_form_filled' => $this->is_form_filled,
            // Load the address relationship with conditional check
            'address' => $this->whenLoaded('address', function () {
                return $this->address;
            }),
        ];
    }
}
