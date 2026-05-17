<?php

namespace App\Support;

use App\Models\DeletionLog;
use Illuminate\Support\Facades\Session;

class DeletionLogger
{
    public static function log(string $entityType, ?int $entityId, string $summary, array $details = []): void
    {
        DeletionLog::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'summary' => $summary,
            'details' => $details ?: null,
            'deleted_by' => Session::get('username'),
            'deleted_at' => now(),
        ]);
    }
}
