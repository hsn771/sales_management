<?php

namespace Database\Seeders;

use App\Models\ReportLine;
use App\Models\Target;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->toDateString();
        $pos = 0;

        $rows = [
            [
                'report_date' => $today,
                'rode' => 'R-101',
                'name' => 'John Wick',
                'target' => 50000,
                'balance' => 7500,
                'over' => 1200,
                'commission' => 250,
                'daily_cost' => 45,
                'ach' => 92,
            ],
            [
                'report_date' => $today,
                'rode' => 'R-102',
                'name' => 'Tony Stark',
                'target' => 100000,
                'balance' => 0,
                'over' => 15000,
                'commission' => 2000,
                'daily_cost' => 120,
                'ach' => 105,
            ],
            [
                'report_date' => $today,
                'rode' => 'R-103',
                'name' => 'Bruce Wayne',
                'target' => 75000,
                'balance' => 41250,
                'over' => 0,
                'commission' => 0,
                'daily_cost' => 80,
                'ach' => 42,
            ],
        ];

        foreach ($rows as $row) {
            $line = ReportLine::query()->create([
                'position' => ++$pos,
            ]);
            $row['report_line_id'] = $line->id;
            Target::query()->create($row);
        }
    }
}
