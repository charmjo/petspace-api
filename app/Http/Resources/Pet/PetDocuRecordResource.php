<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PetDocuRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pathToFile = $this->record_path;
        $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addSeconds(600)) : null;

        return [
            'id' => $this->id,
            'pet_id' => $this->pet_id,
            'added_by_id' => $this->added_by,
            'added_by_first_name' => $this->first_name,
            'added_by_last_name' => $this->last_name,
            'record_path' => $temporaryUrl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'filename'=>$this->filename
        ];
    }
}
