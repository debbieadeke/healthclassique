<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Speciality::insert(
            [
                [
                    'name' => 'Dermatologist',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Paediatrician',
                    'created_at' => now(),
                ]
            ]
        );
    }
}
