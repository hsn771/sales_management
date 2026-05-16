<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_line_id',
        'report_date',
        'rode',
        'name',
        'target',
        'target_percent',
        'balance',
        'over',
        'commission',
        'daily_cost',
        'ach',
        'rode_id',
        'sr_id',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function rodeModel()
    {
        return $this->belongsTo(Rode::class, 'rode_id');
    }

    public function srModel()
    {
        return $this->belongsTo(SR::class, 'sr_id');
    }

    public function reportLine()
    {
        return $this->belongsTo(ReportLine::class);
    }

    /** Per-day snapshot: stored text first, then that day's ids, then line/master. */
    public function displayRode(?string $lineRodeName = null): string
    {
        $stored = trim((string) ($this->rode ?? ''));
        if ($stored !== '') {
            return $stored;
        }
        if ($lineRodeName !== null && trim($lineRodeName) !== '') {
            return trim($lineRodeName);
        }

        return $this->rodeModel?->name ?? '';
    }

    public function displaySr(?string $lineSrName = null): string
    {
        $stored = trim((string) ($this->name ?? ''));
        if ($stored !== '') {
            return $stored;
        }
        if ($lineSrName !== null && trim($lineSrName) !== '') {
            return trim($lineSrName);
        }

        return $this->srModel?->name ?? '';
    }
}
