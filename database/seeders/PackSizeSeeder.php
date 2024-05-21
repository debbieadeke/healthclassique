<?php

namespace Database\Seeders;

use App\Models\PackSize;
use Illuminate\Database\Seeder;

class PackSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackSize::factory()->count(5)->create();
    }
}
