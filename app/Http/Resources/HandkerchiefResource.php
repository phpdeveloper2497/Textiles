<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandkerchiefResource extends JsonResource
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
            'sort_plane' => $this->sort_plane,
            'all_products' => $this->all_products,
            'defective_products' => $this->defective_products,
            'finished_products' => $this->finished_products,
        ];
    }
}
