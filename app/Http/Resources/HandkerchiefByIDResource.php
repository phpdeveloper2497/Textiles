<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandkerchiefByIDResource extends JsonResource
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
            'all_products' => $this->all_products,
            'defective_products' => $this->defective_products,
            'finished_products' => $this->finished_products,
            'not_packaged' => $this->not_packaged,
        ];
    }
}
