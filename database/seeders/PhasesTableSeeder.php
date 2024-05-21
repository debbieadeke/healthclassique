<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Phase;
class PhasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Phase::insert(
            [
                [
                    'name' => 'PHASE A',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PHASE B',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PHASE C',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PHASE D',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PHASE E',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PHASE F',
                    'type' => 'ingredients',
                    'created_at' => now(),
                ],
                [
                    'name' => 'PRIMARY PACKAGING',
                    'type' => 'packaging',
                    'created_at' => now(),
                ],
                [
                    'name' => 'SECONDARY PACKAGING',
                    'type' => 'packaging',
                    'created_at' => now(),
                ],
                [
                    'name' => 'MISCELLANEOUS',
                    'type' => 'miscellaneous',
                    'created_at' => now(),
                ]

            ]
        );
    }
}
