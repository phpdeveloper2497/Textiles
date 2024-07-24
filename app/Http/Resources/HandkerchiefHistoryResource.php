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
//        dd($this->handkerchief->finished_products);
        return [
            'id' => $this->id,
            'user' => $this->user->nickname,
            'storage_in' => $this->storage_in,
            'sold_out' => $this->sold_out,
            'handkerchief' => $this->handkerchief->name,
            'finished_products' => $this->finished_products,
            'defective_products' => $this->defective_products,
            'all_products' => $this->finished_products + $this->defective_products,
//            'not_packaged' => $this->handkerchief->all_products - $this->handkerchief->finished_products - $this->handkerchief->defective_products,
            'sold_products' => $this->sold_products,
            'sold_defective_products' => $this->sold_defective_products,
            'sold_all_products' => $this->sold_products + $this->sold_defective_products,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];


    }
}
