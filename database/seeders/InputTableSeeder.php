<?php

namespace Database\Seeders;

use App\Models\Input;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InputTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Input::insert(
            [
                [
                    'name' => 'Liquid Parrafin',
                    'type' => 'ingredient',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Glycerin',
                    'type' => 'ingredient',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Petrolatum',
                    'type' => 'ingredient',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Water',
                    'type' => 'ingredient',
                    'created_at' => now(),
                ],
                [
                    'name' => 'P. Pack :: Printed Pump Bottles 400g',
                    'type' => 'packaging',
                    'created_at' => now(),
                ],
                [
                    'name' => 'P. Pack :: Wrapping Paper 1x6pcs',
                    'type' => 'packaging',
                    'created_at' => now(),
                ]
                ,
                [
                    'name' => 'S. Pack :: Cartons & Taping',
                    'type' => 'packaging',
                    'created_at' => now(),
                ]
                ,
                [
                    'name' => 'Labour',
                    'type' => 'miscellaneous',
                    'created_at' => now(),
                ]

            ]
        );
    }
}
