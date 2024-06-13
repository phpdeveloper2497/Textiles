<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoxResource extends JsonResource
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
            "name" =>$this->name,
            "per_liner_meter" =>$this->per_liner_meter,
            "remainder" =>$this->remainder,
            "sort_by" =>$this->sort_by,
            "image_path" =>config('app.url')."/storage/". $this->image_path,
            "created_at" =>$this->created_at,
            "updated_at" =>$this->updated_at,
        ];
    }
}
