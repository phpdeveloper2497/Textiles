<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InprogressBoxHistoryResource extends JsonResource
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
            "box" => $this->box->name,
            "per_pc_meter" => $this->per_pc_meter,
            "pc" => $this->pc,
            "length" => $this->length,
//            "created_at" => $this->created_at,
//            "updated_at" => $this->updated_at,
        ];
    }
}
