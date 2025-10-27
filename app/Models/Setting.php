<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable=[
     'logo','email','phone','linkedin','facebook','twitter','work_time','bag_price',
    'about_title','about_description','about_image','packagestatus',
    'home_cost',
    'home_speed',
    'home_pay',
    'home_image',
    'home_work',
    'home_create_description',
    'home_start_description',
    'home_pay_description',
    'home_recive_description',
    'slider_title','slider_description',
    'pages_image','home_title','home_description',
    'privacy_policy','terms_conditions','messenger','whatsapp'
    ,'support_description',
    'calc_description',
    'no_payment',
    'our_goals_description',
    'our_goal_title',
    'meta_description',
    'meta_keyword',
    'terms_conditions_en',
    'privacy_policy_en',
    'instagram','snapchat','tiktok','commission'

    ];
}
