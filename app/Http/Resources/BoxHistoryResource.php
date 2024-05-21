<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "box_id" => $this->box_id,
            "user_id" => $this->user_id,
            "in_storage" => $this->in_storage,
            "out_storage" => $this->out_storage,
            "returned" => $this->returned,
            "per_pc_meter" => $this->per_pc_meter,
            "pc" => $this->pc,
            "length" => $this->length,
            "remainder" => $this->box->remainder,
            "commentary" => $this->commentary,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
