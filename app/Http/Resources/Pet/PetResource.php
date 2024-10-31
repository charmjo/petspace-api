<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
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
            'name' => $this->name,
            'breed' => $this->animal_type,
            'dob' => $this->dob,
            'gender' => $this->gender,
        ];
    }
}
