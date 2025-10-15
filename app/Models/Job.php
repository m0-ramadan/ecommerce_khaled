<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Job extends Model
{
    use HasFactory, HasTranslations;

    const RULES = [
        'title' => 'nullable|array',
        'description' => 'nullable|array',
        'title_ar' => 'required|string',
        'description_ar' => 'required|string',
        'title_en' => 'required|string',
        'description_en' => 'required|string',
        'salary' => 'nullable|integer',
        'status' => 'required|in:0,1',
    ];

    protected $fillable = [
        'title',
        'description',
        'salary',
        'status',
    ];

    protected $translatable = [
        'title',
        'description',
    ];

    public function employees()
    {
        return $this->hasMany(EmployeeJob::class);
    }
}
