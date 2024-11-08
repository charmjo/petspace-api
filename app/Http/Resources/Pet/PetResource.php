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
            'animal_type' => $this->animal_type,
            'color' => $this->color,
            'bio' => $this->bio,
            'is_tagged' => $this->is_tagged,
            'microchip_id' => $this->microchip_id,
            'pet_image' => $temporaryUrl
        ];
    }
}
