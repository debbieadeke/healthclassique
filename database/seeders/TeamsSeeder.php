<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::insert(
            [
                [
                    'name' => 'Epimol Team',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Oatveen Team',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Zelaton Team',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Tender Team',
                    'created_at' => now(),
                ]
            ]
        );
    }
}
