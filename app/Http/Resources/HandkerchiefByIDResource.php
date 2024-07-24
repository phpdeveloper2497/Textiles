<?php

namespace App\Http\Resources;

use App\Models\HandkerchiefHistory;
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
        $notPackaged = null;

//        if (HandkerchiefHistory::where('storage_in', true)->exists()) {
//            $notPackaged = $this->all_products - $this->finished_products - $this->defective_products;
//        }

        return [
            'id' => $this->id,
            'all_products' => $this->finished_products +  $this->defective_products - $this->sold_products -  $this->sold_defective_products,
            'finished_products' => $this->finished_products,
            'defective_products' => $this->defective_products,
            'sold_products' => $this->sold_products,
            'sold_defective_products' => $this->sold_defective_products
        ];
    }
}
