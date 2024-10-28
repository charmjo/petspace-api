<?php

namespace App\Http\Resources\Pet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PetCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            // 'meta' => [
            //     'total' => $this->count(),
            //     'current_page' => $this->currentPage(),
            //     'last_page' => $this->lastPage(),
            // ],
        ];
    }
}
