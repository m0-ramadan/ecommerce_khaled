<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Country extends Model
{
    use HasTranslations;
    public $translatable = ['name'];
    protected $table = 'countries';
    public $timestamps = true;
    protected $fillable = ['name', 'country_ref_code', 'phone_prefix'];

    protected function countryRefCode(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper(substr($value, 0, 2)),
        );
    }
}
