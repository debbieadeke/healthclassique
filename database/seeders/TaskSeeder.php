<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create(['name' => 'Hourly Roundrobin']);
        Task::create(['name' => 'Every Night Roundrobin']);
        Task::create(['name' => '45 Mins Mandatory Every Evening']);
    }
}
