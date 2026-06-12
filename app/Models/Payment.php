<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'amount', 'status', 'provider',
        'provider_txn_id', 'hold_captured_at', 'paid_at',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'hold_captured_at'=> 'datetime',
        'paid_at'         => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
