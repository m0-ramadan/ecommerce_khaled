<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    public const STATUS_PENDING_DELIVERY = 'pending_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_READ = 'read';
    public const STATUS_FAILED = 'failed';

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'to_phone',
        'wamid',
        'template',
        'status',
        'meta_status',
        'error_code',
        'error_message',
        'sent_at',
        'delivered_at',
        'read_at',
        'failed_at',
        'payload',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'failed_at' => 'datetime',
        'payload' => 'array',
    ];

    /**
     * تطبيق حالة واردة من الـ Webhook.
     * "sent" مش تأكيد تسليم، فبنفضل pending_delivery لحد delivered/read/failed.
     */
    public function applyMetaStatus(string $status, ?array $errors = null): void
    {
        $status = strtolower($status);
        $this->meta_status = $status;

        if ($status === 'delivered') {
            $this->status = self::STATUS_DELIVERED;
            $this->delivered_at = $this->delivered_at ?: now();
        } elseif ($status === 'read') {
            $this->status = self::STATUS_READ;
            $this->delivered_at = $this->delivered_at ?: now();
            $this->read_at = $this->read_at ?: now();
        } elseif ($status === 'failed') {
            $this->status = self::STATUS_FAILED;
            $this->failed_at = $this->failed_at ?: now();
            $this->error_code = (string) (data_get($errors, '0.code') ?? $this->error_code);
            $this->error_message = (string) (
                data_get($errors, '0.error_data.details')
                ?? data_get($errors, '0.message')
                ?? $this->error_message
            );
        }

        $this->save();
    }
}
