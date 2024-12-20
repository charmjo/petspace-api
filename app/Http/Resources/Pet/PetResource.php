<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // set a temporary URL for images
        $pathToFile = $this->image_storage_path;
        $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addSeconds(600)) : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'breed' => $this->breed,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'animal_type' => ucfirst(strtolower($this->animal_type)),
            'color' => $this->color,
            'bio' => $this->bio,
            'is_microchipped' => $this->is_microchipped,
            'is_spayed_neutered' => $this->is_spayed_neutered,
            // not ideal but this fixes the issue without needing to re-migrate the db
            'microchip_id' => (int) $this->microchip_id,
            'pet_image' => $temporaryUrl
        ];
    }
}
