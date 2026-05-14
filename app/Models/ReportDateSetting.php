<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportDateSetting extends Model
{
    protected $table = 'report_date_settings';

    protected $fillable = [
        'report_date',
        'percent_base',
        'apr_percent',
    ];

    protected $casts = [
        'report_date' => 'date',
        'percent_base' => 'decimal:2',
        'apr_percent' => 'decimal:4',
    ];
}
