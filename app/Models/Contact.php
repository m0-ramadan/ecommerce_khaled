<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contact extends Model
{
    protected $table = 'contacts';
    public $timestamps = true;
    
    use HasTranslations;
    public $translatable = ['about_footer'];
    

    public $fillable = [
        'facebook',
        'instagram',
        'twitter',
        'address',
        'details',
        'replacement',
        'judgments',
        'phone',
        'location',
        'image',
        'address',
        'app_name',
        'mail',
        'construction_link',
        'video_link',
        'youtube',
        'tiktok',
        'head_image',
        'bottom_image',
        'head_color',
        'font_color',
        'offer_image',
        'insp_mop_img',
        'tax_rate',
        'zakat_percentage',
        'insurance',
        'insp_web_img',
        'advertising_and_development',
        'registered_notification_title',
        'registered_notification_content',
        'about_footer'
    ];
}
