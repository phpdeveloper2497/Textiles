<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreHandkerchiefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "sort_plane" => $this->sort_plane,
            "all_products" => $this->all_products,
            "finished_products" => $this->finished_products,
            "defective_products" => $this->defective_products,
        ];
    }
}
