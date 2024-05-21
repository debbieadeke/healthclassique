<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::insert(
            [
                [
                    'first_name' => 'George',
                    'last_name' => 'Okech',
                    'category' => 'Doctor',
                    'location_id' => 1,
                    'title_id' => 1,
                    'speciality_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'first_name' => 'Mary',
                    'last_name' => 'Wamalwa',
                    'category' => 'Doctor',
                    'location_id' => 2,
                    'title_id' => 1,
                    'speciality_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'first_name' => 'Peter',
                    'last_name' => 'Kamau',
                    'category' => 'Doctor',
                    'location_id' => 1,
                    'title_id' => 1,
                    'speciality_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'first_name' => 'Phoebe',
                    'last_name' => 'Karisa',
                    'category' => 'Doctor',
                    'location_id' => 2,
                    'title_id' => 1,
                    'speciality_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'first_name' => 'Patel',
                    'last_name' => 'Singh',
                    'category' => 'Doctor',
                    'location_id' => 1,
                    'title_id' => 1,
                    'speciality_id' => 1,
                    'created_at' => now(),
                ]
            ]
        );
    }
}
