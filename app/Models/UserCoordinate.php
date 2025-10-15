<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCoordinate extends Model
{
    use HasFactory;

    const RULES = [
        'firebase_token' => 'required|string|max:255',
        'latitude' => 'required|numeric|min:-90|max:90',
        'longitude' => 'required|numeric|min:-180|max:180',
    ];

    protected $table = 'user_coordinates';

    protected $fillable = [
        'firebase_token',
        'latitude',
        'longitude',
        'country_name',
        'state_name',
        'city_name',
        'address',
        'registered_notification_count',
        'last_registered_notification',
    ];

    public static function getUserStatistics()
    {
        return self::select('city_name', 'state_name', 'country_name', DB::raw('COUNT(*) as user_count'))
            ->groupBy('city_name', 'state_name', 'country_name')
            ->take(10)
            ->get();
    }
}
