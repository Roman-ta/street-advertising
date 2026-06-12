<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class OrderItem extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    protected $fillable = [
        'order_id', 'spot_id', 'date_from', 'date_to',
        'price', 'commission',
        'placement_started_at', 'placement_ends_at',
    ];

    protected $casts = [
        'date_from'           => 'date',
        'date_to'             => 'date',
        'price'               => 'decimal:2',
        'commission'          => 'decimal:2',
        'placement_started_at'=> 'datetime',
        'placement_ends_at'   => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }

    public function payout(): HasOne
    {
        return $this->hasOne(Payout::class);
    }
}
