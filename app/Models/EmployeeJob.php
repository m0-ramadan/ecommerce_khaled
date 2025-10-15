<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeJob extends Model
{
    use HasFactory;

    const UPLOAD_PATH = 'uploads/employees_jobs';

    const RULES = [
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'cv' => 'required|file|mimes:pdf',
    ];

    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'cv',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
