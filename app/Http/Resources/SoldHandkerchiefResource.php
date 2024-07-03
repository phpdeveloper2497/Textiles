<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoldHandkerchiefResource extends JsonResource
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
            'user' => $this->user->nickname,
            'sold_out' => $this->sold_out,
            'handkerchief' => $this->handkerchief->name,
            'sold_products' => $this->sold_products,
            'sold_defective_products' => $this->sold_defective_products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
