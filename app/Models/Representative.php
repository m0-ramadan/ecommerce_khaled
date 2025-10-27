<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;

class Representative extends Authenticatable
{
    use HasApiTokens, HasFactory;
    use SoftDeletes;

    protected $table = 'representatives';

    protected $fillable = [
        'name',
        'password',
        'city',
        'email',
        'phone',
        'phone2',
        'status',
        'birth_date',
        'passport_photo',
        'passport_number',
        'card_photo',
        'card_number',
        'vehicle_photo',
        'vehicle_id',
        'personal_license_photo',
        'personal_license_number',
        'vehicle_license_photo',
        'vehicle_license_number',
        'image_profile',
        'address',
        'gender',
        'commission',
        'code','type'
    ];

    protected $hidden = [
        'password',
    ];
    public $timestamps = false;

    protected $casts = [
        'birth_date' => 'date',
        'status' => 'boolean',
    ];
    public function region()
    {
        return $this->belongsTo(Region::class, 'city');
    }

    // public function deviceToken()
    // {
    //     return $this->morphMany(DeviceToken::class, 'model');
    // }
    public function deviceToken()
    {
        return $this->hasMany(DeviceToken::class, 'model_id')->where('model_type', 'App\Models\Representative');
    }


    public function getGenderTypeNameAttribute()
    {
        return match ((int) $this->gender) {
            1 => 'ذكر',
            2 => 'أنثى',
            default => 'غير محدد',
        };
    }


    public function getStatusLabelAttribute()
    {
        return match ((int) $this->status) {
            0 => 'قيد الانتظار',
            1 => 'مفعل',
            2 => 'محظور',
            default => 'غير معروف',
        };
    }


    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}