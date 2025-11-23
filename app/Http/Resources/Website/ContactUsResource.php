<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'phone'       => $this->phone,
            'email'       => $this->email,
            'company'     => $this->company,
            'message'     => $this->message,
            'created_at'  => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}