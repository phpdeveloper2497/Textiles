<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'nickname' => $this->nickname,
            'role' => $this->whenLoaded('roles', function () {
                return [
                    'id' => $this->roles->first()?->id,
                    'name' => $this->roles->first()?->name,
                ];
            }),
        ];
    }
}
