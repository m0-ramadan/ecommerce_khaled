<?php

namespace App\Http\Resources;

use App\Models\NewSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { // ->pluck('value', 'name')->map(function ($value) {
    //     return
    // })->toArray()
        return [
            'name' => $this->name,
            'value' => 'public/' .NewSetting::UPLOAD_PATH . $this->value,
            'title' => $this->title,
        ];
    }
}
