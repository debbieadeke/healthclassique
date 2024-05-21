<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert(
            [
                [
                    'name' => 'Epimol-B Jn 125ml',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Epimol-B Jn 400ml',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Epimol-B AP 125ml',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Epimol-B AP 400ml',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Epimol-B Plu 400ml',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Synolin Drops',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Synolin Spray',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Synolin S. Aspirator',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Enterobasila',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Niacin-B gel',
                    'created_at' => now(),
                ],
                [
                    'name' => 'Sahara SPF 50',
                    'created_at' => now(),
                ],
                [
                    'name' => 'HC-Dine',
                    'created_at' => now(),
                ],
                [
                    'name' => 'HealthyFlow',
                    'created_at' => now(),
                ],
                [
                    'name' => 'HealthyC',
                    'created_at' => now(),
                ]
            ]
        );
    }
}
