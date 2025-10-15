<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;


class Setting extends Model
{
    use Notifiable;
     use HasTranslations;
    public $translatable = ['offer_image','about_footer'];
    protected $table='settings';
    public $timestamps=true;

    public $fillable =['image','about_footer','offer_image','promo_code_name','key_offer','link_video','about_us','Terms_Conditions','whatsApp','keywords','metadescription', 'cashback_value','mount_pound','lower_cashback'];

    public function clientCategory()
    {
        return $this->hasMany(ClientCategory::class);
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id');
    }

    public function subCategories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

}
