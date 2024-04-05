<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBoxHistoryResource extends JsonResource
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
            "box_id" => $this->box_id,
            "user_id" => $this->user_id,
            "per_pc_meter" => $this->per_pc_meter,
            "pc" => $this->pc,
            "length" => $this->length,
            "commentary" => $this->commentary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
