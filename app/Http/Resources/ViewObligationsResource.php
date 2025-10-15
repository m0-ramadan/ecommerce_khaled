<?php

namespace App\Http\Resources;

use App\Models\Obligations;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewObligationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'subject' => $this->subject ?? '',
            'monthly_dues' => $this->monthly_dues ?? 0,
            'total_money' => $this->total_money ?? "",
            'payment_method' => $this->payment_method ?? "",
            'annual_dues' => $this->annual_dues ?? 0,
            'file' => $this->file ? 'https://taqiviolet.com/public/' . $this->file : null,
            'status' => 1,
            'date' => date('Y-m-d', strtotime($this->date)) ?? ''
        ];
    }
}
