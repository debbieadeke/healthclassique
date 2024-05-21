<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\SalesCall;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanUpDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanupduplicates';

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
        $results = DB::table('clients')
            ->select('first_name', 'last_name', DB::raw('COUNT(*) as duplicate_count'))
            ->groupBy('first_name', 'last_name')
            ->havingRaw('COUNT(*) > 1')
            ->orderBy('duplicate_count')
            ->get();
        foreach ($results as $result) {
            $first_name = $result->first_name;
            $last_name = $result->last_name;

            $clients = Client::where('first_name', '=', $first_name)->where('last_name', '=', $last_name)->get('id');
            $id = $clients[0]->id;
            foreach ($clients as $client) {
                $appointments = Appointment::where('client_id', '=', $client->id)->get();
                foreach ($appointments as $appointment) {
                    $appointment->client_id = $id;
                    $appointment->update();
                }


                $salescalls = SalesCall::where('client_id', '=', $client->id)->get();
                foreach ($salescalls as $salescall) {
                    $salescall->client_id = $id;
                    $salescall->update();
                }

                if ($id != $client->id) {
                    Client::destroy($client->id); // Replace $id with the actual primary key value
                }
            }

        }


    }
}
