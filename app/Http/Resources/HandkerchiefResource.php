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
//        dd($this->handkerchiefHistories->first()->finished_products);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sort_plane' => $this->sort_plane,
            'all_products' => $this->finished_products +  $this->defective_products - $this->sold_products -  $this->sold_defective_products,
            'finished_products' => $this->finished_products,
            'defective_products' => $this->defective_products,
            "image_path" =>config('app.url')."/storage/". $this->image_path,
        ];
    }
}
