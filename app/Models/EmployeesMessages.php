<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeesMessages extends Model
{
    protected $table = 'employees_messages';
    public $timestamps = true;

    public $fillable = ['name','email','address' , 'message' , 'user_id' , 'status'];
}

