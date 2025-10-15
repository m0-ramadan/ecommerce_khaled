<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
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
            "id" => $this->id,
            "sender_id" => $this->sender_id,
            "reserver_id" => $this->reserver_id,
            "sender_name" => $this->sender->name,
            "reserver_name" => $this->reserver->name,
            "parent_id" =>$this->parent_id,
            "title" => $this->title,
            "content" => $this->content,
            "created_at" => $this->created_at->format('Y-m-d h:i A'),
            "sender_read_at" =>  $this->sender_read_at,
            "reserver_read_at" =>  $this->reserver_read_at,
            "auth_id" => auth('api')->id(),
            'emails' => EmailResource::collection($this->children),
        ];
    }
}
