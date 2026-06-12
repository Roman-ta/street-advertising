<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotAvailability extends Model
{
    protected $fillable = [
    'spot_id', 'date_from', 'date_to', 'status',
];

    protected $casts = [
        'date_from' => 'date',
        'date_to'   => 'date',
    ];

    public function spot(): BelongsTo
    {
        return $this->belongsTo(Spot::class);
    }
}
