<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpotType extends Model
{
    protected $fillable = [
        'slug', 'name_ru', 'name_ro', 'name_en', 'icon', 'category', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function spots(): HasMany
    {
        return $this->hasMany(Spot::class, 'type', 'slug');
    }

    public function getNameAttribute(): string
    {
        return match(app()->getLocale()) {
            'ro' => $this->name_ro,
            'en' => $this->name_en,
            default => $this->name_ru,
        };
    }
}
