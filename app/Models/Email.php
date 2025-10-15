<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{

    const RULES = [
        'phone' => 'required_without:parent_id|exists:clients,phone',
        'parent_id' => 'required_without:phone|exists:emails,id',
        'title' => 'required_with:phone|string',

        'content' => 'required|string',

        'reserver_id' => 'nullable|exists:clients,id',
        'sender_id' => 'nullable|exists:clients,id',
        'email_sender_id' => 'nullable|exists:clients,id',
    ];

    protected $fillable = [
        'sender_id',
        'reserver_id',
        'email_sender_id',
        'parent_id',
        'title',
        'content',
        'reserver_read_at',
        'sender_read_at',
    ];

    public function sender()
    {
        return $this->belongsTo(Client::class, 'sender_id');
    }

    public function reserver()
    {
        return $this->belongsTo(Client::class, 'reserver_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
