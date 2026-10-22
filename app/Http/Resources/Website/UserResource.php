<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'points'     => (int) ($this->points ?? 0),
            'phone_verified_at' => $this->phone_verified_at?->format('Y-m-d H:i:s'),
            'image' => get_user_image($this->image),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
