<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications'; // اسم الجدول
    protected $primaryKey = 'id'; // المفتاح الأساسي
    public $incrementing = false; // تعطيل الزيادة التلقائية للمفتاح الأساسي
    protected $keyType = 'string'; // نوع المفتاح الأساسي

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array', // تحويل حقل data إلى array
    ];
    public function notifiable()
    {
        return $this->morphTo();
    }
}
