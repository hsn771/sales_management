<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletionLog extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id',
        'summary',
        'details',
        'deleted_by',
        'deleted_at',
    ];

    protected $casts = [
        'details' => 'array',
        'deleted_at' => 'datetime',
    ];

    public function entityTypeLabel(): string
    {
        return match ($this->entity_type) {
            'target' => 'Target',
            'rode' => 'Rode',
            'sr' => 'SR',
            'report_line' => 'Report row',
            default => ucfirst(str_replace('_', ' ', $this->entity_type)),
        };
    }
}
