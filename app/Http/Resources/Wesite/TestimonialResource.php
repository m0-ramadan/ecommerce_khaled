<?php

namespace App\Http\Resources\Wesite;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
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
    'name' => $this->name,
    'city' => $this->city,
    'rating' => $this->rating,
    'review' => $this->review,
    'avatar' => $this->avatar ? asset($this->avatar) : null,
];
    }
}
