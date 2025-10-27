<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Define constants for type_task
    const TYPE_COLLECT = 1;
    const TYPE_SETTLE = 2;
    const TYPE_DELIVER_RETURNS = 3;

    // Define constants for duration
    const DURATION_MONTH = 1;
    const DURATION_TWO_MONTHS = 2;
    const DURATION_THREE_MONTHS = 3;

    // Define constants for receive_via
    const RECEIVE_BRANCH = 1;
    const RECEIVE_REPRESENTATIVE = 2;
    const RECEIVE_BANK = 3;

    protected $fillable = [
        'type_task',
        'number_order',
        'address',
        'date_implementation',
        'duration',
        'notes',
        'receive_via',
        'person_id',
        'person_type',
        'value_amount'
    ];

    /**
     * Get the associated person (polymorphic relationship).
     */
    public function person()
    {
        return $this->morphTo();
    }
}
