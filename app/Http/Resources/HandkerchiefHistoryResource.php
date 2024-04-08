<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandkerchiefHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'handkerchief_id' => $this->handkerchief_id,
            'user_id' => $this->user_id,
            'all_products' => $this->all_products,
            'defective_products' => $this->defective_products,
            'finished_products' => $this->finished_products,
            'sold_products' => $this->sold_products,
            'sold_defective_products' => $this->sold_defective_products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
