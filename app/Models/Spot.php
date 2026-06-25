<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

class Spot extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasTranslations;
    public array $translatable = ['title', 'description'];
    protected $fillable = [
        'partner_id', 'title', 'type', 'address', 'lat', 'lng',
        'city', 'district', 'size_w', 'size_h', 'price_month',
        'description', 'lighting', 'traffic',
        'file_types_allowed', 'status', 'translations',
    ];

    protected $casts = [
        'lighting'           => 'boolean',
        'file_types_allowed' => 'array',
        'translations'       => 'array',
        'lat'                => 'decimal:7',
        'lng'                => 'decimal:7',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SpotPhoto::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(SpotAvailability::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

// Главное фото
    public function mainPhoto(): HasOne
    {
        return $this->hasOne(SpotPhoto::class)->where('is_main', true);
    }
    // Сколько платформа заработала на этой площадке (10% комиссия)
    public function getTotalCommissionAttribute(): float
    {
        return $this->orderItems()
            ->whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
            ->sum('commission');
    }

// Сколько партнёру причитается с этой площадки (90%, ещё не выплачено)
    public function getPendingPayoutAttribute(): float
    {
        return $this->orderItems()
            ->whereHas('order', fn($q) => $q->whereIn('status', ['active', 'completed']))
            ->whereDoesntHave('payout')
            ->sum('price');
    }
}
