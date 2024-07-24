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
        if ($this->is_admin) {
            return []; // Admin foydalanuvchini qaytarmaslik uchun bo'sh array qaytarish
        }

        return [
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'nickname' => $this->nickname,
            'roles' => RoleListResource::collection($this->whenLoaded('roles'))
        ];
    }
}
