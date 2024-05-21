<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientGpsRecord;
use App\Models\Facility;
use App\Models\GPSRecord;
use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GPSLocationController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        // Doctors
        $doctors = $user->clients->unique()->map(function ($doctor) {
            $doctor->name = $doctor->first_name . ' ' . $doctor->last_name;
            $doctor->client_type = 'Doctor'; // Add client_type attribute
            return $doctor;
        });
        $user_id = $user->id;


        // Clinic
        $clinic = $user->facilities->unique()->map(function ($facility) {
            $facility->client_type = 'Clinic';
            return $facility;
        });

        // Pharmacy

        $pharmacy = $user->pharmacies->unique()->map(function ($pharmacy) {
            $pharmacy->client_type = 'Pharmacy';
            return $pharmacy;
        });

        $clients = $doctors->merge($clinic)->merge($pharmacy);


        $gpsRecords = ClientGpsRecord::where('user_id',$user_id)->get();

        //return $clients;

        $data['pagetitle'] = 'GPS Location';
        $data['clients'] = $clients;
        $data['locations'] = $gpsRecords;
        return view('gps.index', ['data'=>$data]);
    }
    public function store_client_gps(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'client_id' => 'required|numeric',
                'client_type' => 'required|string',
                'client_name' => 'required|string',
            ]);

            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            ClientGpsRecord::create([
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'client_id'=> $validatedData['client_id'],
                'client_type'=> $validatedData['client_type'],
                'client_name'=> $validatedData['client_name'],
                'user_id'=>  $user_id,
            ]);

            return redirect()->route('gps.index')->with('success', 'Client GPS stored successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Save Client Location ' . $e->getMessage());
        }
    }
    public function store_gps(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);


            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;
            $now = Carbon::now();

            // Check if the current time is between 7:00 AM and 6:00 PM
            if ($now->hour >= 7 && $now->hour < 18) {
                // Check if the user has already sent their GPS location today
                $firstRecord = $user->gpsRecords()
                    ->where('gps_type', 'Start')
                    ->whereDate('recorded_at', $now->toDateString())
                    ->first();
                if ($firstRecord) {
                    return response()->json(['message' => 'GPS location already sent today'], 400);
                }
            } else {
                // If the current time is not between 8:00 AM and 6:00 PM, return an error response
                return response()->json(['message' => 'GPS location can only be sent between 7:00 AM and 6:00 PM'], 400);
            }

            GPSRecord::create([
                'user_id' => $user->id,
                'gps_type' => 'Start',
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
                'recorded_at' => $now,
            ]);

            return response()->json(['message' => 'GPS record stored successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error storing GPS record', 'error' => $e->getMessage()], 500);
        }
    }

    public function interval_gps(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $user = Auth::user();

            // If user is not logged in or authenticated
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }

            // Check if the current time is between 8:00 AM and 6:00 PM
            $now = Carbon::now();
            if ($now->hour >= 8 && $now->hour < 18) {
                // Create GPS record
                GPSRecord::create([
                    'user_id' => $user->id,
                    'gps_type' => 'Interval',
                    'latitude' => $validatedData['latitude'],
                    'longitude' => $validatedData['longitude'],
                    'recorded_at' => $now,
                ]);

                return response()->json(['message' => 'GPS record stored successfully'], 200);
            } else {
                // If the current time is not between 8:00 AM and 6:00 PM, return an error response
                return response()->json(['message' => 'GPS location can only be sent between 8:00 AM and 6:00 PM'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error storing GPS record', 'error' => $e->getMessage()], 500);
        }
    }
}
