<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportLine extends Model
{
    protected $fillable = [
        'rode_id',
        'sr_id',
        'position',
    ];

    public function rode(): BelongsTo
    {
        return $this->belongsTo(Rode::class, 'rode_id');
    }

    public function sr(): BelongsTo
    {
        return $this->belongsTo(SR::class, 'sr_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }
}
