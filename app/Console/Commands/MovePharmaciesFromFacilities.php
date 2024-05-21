<?php

namespace App\Console\Commands;

use App\Models\Facility;
use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MovePharmaciesFromFacilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:pharmacies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pharmacies = Facility::where('facility_type', 'Pharmacy')
            ->whereNull('updated_at')
            ->orderBy('name', 'asc')
            ->get();

        foreach ($pharmacies as $pharmacy) {
            $oldid = $pharmacy->id;
            $name = $pharmacy->name;

            $newpharmacy = Pharmacy::where('name', '=', $name)->pluck('id');

            $newid = $newpharmacy[0];

            $results = DB::select("SELECT user_id, class FROM facility_user WHERE facility_id = $oldid");

            foreach ($results as $result) {
                DB::table('pharmacy_user')->insert([
                        'user_id' => $result->user_id,
                        'pharmacy_id' => $newid,
                        'class' => $result->class,
                    ]
                );

                $pharmacy->updated_at = Carbon::now();
                $pharmacy->update();
            }
        }

    }
}
