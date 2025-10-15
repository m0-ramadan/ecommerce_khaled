<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqListAnswer extends Model
{
    protected $table= 'faqlistanswer';
    public $timestamps = true;
    public $translatable = ['answer'];
    public $fillable = ['id_faq','answer'];
}
