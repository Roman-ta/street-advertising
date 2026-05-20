<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotPhoto extends Model
{
    protected $fillable = [
        'spot_id',
        'path',
        'sort_order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
