<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareList extends Model
{
    protected $table = 'share_lists';
    public $timestamps = true;

    public $fillable = ['roster_id','client_id','type'];
}
