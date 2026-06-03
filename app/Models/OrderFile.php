<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFile extends Model
{
    protected $fillable = [
        'order_id',
        'uploader_id',
        'type',
        'path',
        'mime_type',
        'size_bytes',
        's3_key',
        'signed_url_expires_at',
    ];

    protected $casts = [
        'signed_url_expires_at' => 'datetime',
        'size_bytes'            => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
