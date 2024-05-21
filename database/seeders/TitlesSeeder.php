<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Title;

class TitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Title::insert(
            [
                [
                    'name' => 'Dr',
                    'created_at' => now(),
                ],
				[
                    'name' => 'Mrs',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Miss',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Mr',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Prof',
                    'created_at' => now(),
                ]
                
            ]
        );
    }
}
