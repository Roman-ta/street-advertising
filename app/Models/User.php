<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'lang',
        'is_active', 'legal_signed', 'legal_signed_at',
        'idno', 'iban', 'bank_name', 'legal_address',
        'profile_complete', 'telegram_chat_id',
    ];

    protected $casts = [
        'legal_signed'    => 'boolean',
        'profile_complete'=> 'boolean',
        'is_active'       => 'boolean',
        'legal_signed_at' => 'datetime',
    ];

// Партнёр → его площадки
    public function spots(): HasMany
    {
        return $this->hasMany(Spot::class, 'partner_id');
    }

// Клиент → его заказы
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'client_id');
    }

// Партнёр → его выплаты
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'partner_id');
    }
}
