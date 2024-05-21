<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Facility::insert(
            [
                [
                    'name' => 'RFH Syokimau',
					'facility_type' => 'Clinic',
                    'location_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'name' => 'AAR Syokimau',
					'facility_type' => 'Clinic',
                    'location_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Aga Khan Hospital, Kisumu',
					'facility_type' => 'Clinic',
                    'location_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'name' => 'The Nairobi Hospital',
					'facility_type' => 'Clinic',
                    'location_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Malibu Pharmacy',
					'facility_type' => 'Pharmacy',
                    'location_id' => 1,
                    'created_at' => now(),
                ],
				[
                    'name' => 'Syokimau Chemist',
					'facility_type' => 'Pharmacy',
                    'location_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Matokeo Pharmacy',
					'facility_type' => 'Pharmacy',
                    'location_id' => 1,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Goodlife Chemists',
					'facility_type' => 'Pharmacy',
                    'location_id' => 2,
                    'created_at' => now(),
                ],
                [
                    'name' => 'Nairobi Pharmacy',
					'facility_type' => 'Pharmacy',
                    'location_id' => 1,
                    'created_at' => now(),
                ],
            ]
        );
    }
}
