<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obligations extends Model
{
    protected $table = 'obligations';

    const PATH = 'obligations';

    public $fillable = [
        'subject',
        'monthly_dues',
        'annual_dues',
        'total_money',
        'date',
        'type',
        'payment_method',
        'client_id',
        'file',
    ];

    const OBLIGATION_TYPE =[
        'cost' => 1,
        'operational' => 2,
        'foundational' => 3,
    ];

    public static function getTypeValue(string $name)
    {
        if (in_array($name, self::OBLIGATION_TYPE)) {
            return self::OBLIGATION_TYPE[$name];
        }

        return self::OBLIGATION_TYPE['cost'];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
