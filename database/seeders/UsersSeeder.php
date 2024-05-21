<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super.admin@healthclassique.com',
            'password' => Hash::make('Nairobi@123'),
            'territory_id' => 1
        ]);
        User::create([
			'first_name' => 'Job',
            'last_name' => 'Okoth',
            'email' => 'jobokoth@gmail.com',
            'password' => Hash::make('Nairobi@123'),
            'territory_id' => 2
        ]);
        User::create([
			'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test.user@healthclassique.com',
            'password' => Hash::make('Nairobi@123'),
            'territory_id' => 1
        ]);
		User::create([
			'first_name' => 'Production',
            'last_name' => 'User',
            'email' => 'production@healthclassique.com',
            'password' => Hash::make('Nairobi@123'),
            'territory_id' => 2
        ]);
    }
}
