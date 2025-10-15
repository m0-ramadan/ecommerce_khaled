<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaTag extends Model
{
    protected $table = 'meta_tags';
    public $timestamps = true;

    public $fillable = ['product_id','text'];
}
