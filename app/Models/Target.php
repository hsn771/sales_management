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
}
