<?php

namespace Database\Seeders;

use App\Models\SubTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubTask::create(['name' => 'Pray / declare powerful words e.g. regarding finances, career, business', 'task_id' => 1]);
        SubTask::create(['name' => 'Pray / declare scripture e.g. debt cancellation', 'task_id' => 1]);
        SubTask::create(['name' => 'Sow something today, even Ksh 1. Giving compounding should be on track. Have I paid tithe today?', 'task_id' => 1]);
        SubTask::create(['name' => 'Track my finances. Debt repayment status', 'task_id' => 1]);

        SubTask::create(['name' => 'QT with kids. KCM with Sarah. Then 30mins Roundrobin', 'task_id' => 2]);

        SubTask::create(['name' => 'Job Applications', 'task_id' => 3]);
        SubTask::create(['name' => 'Lintena Development', 'task_id' => 3]);
    }
}
