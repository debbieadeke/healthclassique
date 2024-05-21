<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Territory;

class TerritoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Territory::insert(
            [
                [
                    'name' => 'TM1TERR A',
                    'created_at' => now(),
                ],
                [
                    'name' => 'TM2TERR A',
                    'created_at' => now(),
                ],
                [
                    'name' => 'TM1TERR B',
                    'created_at' => now(),
                ],
                [
                    'name' => 'TM2TERR B',
                    'created_at' => now(),
                ]
            ]
        );
    }
}
