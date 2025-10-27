<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vault extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vaults';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'balance',
        'branch_id',
         'description',
        'currency'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the branch that owns the vault.
     */
    public function branch()
    {
        return $this->belongsTo(Branchs::class, 'branch_id');
    }


    public function getCurrencyNameAttribute(): string
    {
        return match ((int) $this->currency) {
            1 => 'LYD',
            2 => 'EGP',
            3 => 'USD',
            4 => 'TRY',
            default => 'غير معروف',
        };
    }


}