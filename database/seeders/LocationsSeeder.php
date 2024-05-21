<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::insert(
            [
                [
                    'name' => 'Gateway Mall',
                    'longitude' => '-180.3642048',
                    'latitude' => '180.9092169',
                    'territory_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Signature Mall',
                    'longitude' => '-1.4170439',
                    'latitude' => '36.9508686',
                    'territory_id' => 2,
                    'created_at' => now(),
                ]
            ]
        );
    }
}
