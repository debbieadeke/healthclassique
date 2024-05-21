<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Facility;
use App\Models\Location;
use App\Models\Pharmacy;
use App\Models\ProductSample;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\SalesCallDetail;
use App\Models\Speciality;
use App\Models\Title;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = [];

		$user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        if ($user->hasRole('user')) {
            $appointments = Appointment::with(['client', 'user'])
                ->where('user_id', '=', $user->id)
                ->get();
        } else {
            $appointments = Appointment::with(['client', 'user'])
                ->get();
        }

        foreach ($appointments as $appointment) {
            if (is_null($appointment->facility_id)) {
                if (!is_null($appointment->client)) {
					$events[] = [
                    'id' => $appointment->id,
                    'title' => $appointment->client->first_name . ' ' . $appointment->client->last_name . ' ('.$appointment->user->first_name.' ' .$appointment->user->last_name.')',
                    'start' => $appointment->start_time,
                    'end' => $appointment->finish_time,
					];
                } else {
                    if (isset($appointment->pharmacy)) {
                        $events[] = [
                            'id' => $appointment->id,
                            'title' => $appointment->pharmacy->name . ' (' . $appointment->user->first_name . ' ' . $appointment->user->last_name . ')',
                            'start' => $appointment->start_time,
                            'end' => $appointment->finish_time,
                        ];
                    }
                }
            } else {
                $events[] = [
                    'id' => $appointment->id,
                    'title' => $appointment->facility->name . ' ('.$appointment->user->first_name.' ' .$appointment->user->last_name.')',
                    'start' => $appointment->start_time,
                    'end' => $appointment->finish_time,
                ];
            }
        }

        return view('calendar', compact('events'));

    }


    public function userCalender()
    {
        $user = Auth::user();
        $user_id = $user->id;

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        $user = User::findOrFail($user_id);
        $firstName = $user->first_name;
        $lastName = $user->last_name;

        $appointments = Appointment::where('user_id', $user_id)
            ->select(
                'id', // Select the ID column
                DB::raw('DATE(start_time) as date'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count')
            )
            ->groupBy('date')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => 'Appointment', // Title can be anything since we're rendering custom HTML
                'start' => $appointment->date,
                'end' => $appointment->date, // Assuming you want the event to be displayed for the whole day
                'id' => $appointment->id, // Include the appointment ID
                'extendedProps' => [ // Use extendedProps to pass additional data
                    'pending_count' => $appointment->pending_count,
                    'completed_count' => $appointment->completed_count,
                    'total_appointments' => $appointment->pending_count + $appointment->completed_count
                ]
            ];
        }

        //return $events;

        $data['pagetitle'] = "Planner for Sales Reps";
        $data['events'] =  $events;
        return view('planner.userCalender',['data'=>$data]);

    }
    public function index_version2()
    {
        $salesReps = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();


        $data['pagetitle'] = "Planner for Sales Reps";
        $data['reps'] =  $salesReps;
        return view('planner.index',['data'=>$data]);
    }


    public function userPlanner($id)
    {

        $user = User::findOrFail($id);
        $firstName = $user->first_name;
        $lastName = $user->last_name;

        $appointments = Appointment::where('user_id', $user->id)
            ->select(
                'id', // Select the ID column
                DB::raw('DATE(start_time) as date'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count')
            )
            ->groupBy('date')
            ->get();

        $events = [];

        foreach ($appointments as $appointment) {
            $events[] = [
                'title' => 'Appointment', // Title can be anything since we're rendering custom HTML
                'start' => $appointment->date,
                'end' => $appointment->date, // Assuming you want the event to be displayed for the whole day
                'id' => $appointment->id, // Include the appointment ID
                'extendedProps' => [ // Use extendedProps to pass additional data
                    'pending_count' => $appointment->pending_count,
                    'completed_count' => $appointment->completed_count,
                    'total_appointments' => $appointment->pending_count + $appointment->completed_count
                ]
            ];
        }


        $data['pagetitle'] = "Planner for Sales Reps";
        $data['events'] =  $events;
        $data['firstName'] =  $firstName;
        $data['lastName'] =  $lastName;
        return view('planner.userPlanner',['data'=>$data]);
    }

    public function plannerInfo($id)
    {

        $appointmentz = Appointment::with(['client', 'pharmacy', 'facility'])
            ->findOrFail($id);


        $user_id = $appointmentz->user_id;

        // Get the start and end of the week of the selected appointment
        $weekStartDate = Carbon::parse($appointmentz->start_time)->startOfWeek();
        $weekEndDate = Carbon::parse($appointmentz->start_time)->endOfWeek();

        // Initialize an array to store appointments for each day
        $appointmentsByDay = [];

        // Loop through each day of the week
        $currentDay = clone $weekStartDate;
        while ($currentDay <= $weekEndDate) {
            // Retrieve appointments for the current day
            $appointments = Appointment::whereDate('start_time', $currentDay)
                ->where('user_id', $user_id)
                ->with(['facility.location', 'client.locations', 'pharmacy.location']) // Eager load location relationships
                ->get();

            // Modify each appointment to include location name
            foreach ($appointments as $appointment) {
                // Initialize location name variable
                $locationName = null;
                $lastsalescall = null;

                if (!is_null($appointment->facility_id)) {
                    if (!is_null($appointment->facility) && !is_null($appointment->facility->location)) {
                        $locationName = $appointment->facility->location->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->facility_id)
                                     ->where('created_by', $user_id)
                                     ->latest()
                                     ->value('start_time');

                } elseif (!is_null($appointment->client_id)) {
                    if (!is_null($appointment->client) && !is_null($appointment->client->locations)) {
                        $locationName = $appointment->client->locations->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->client_id)
                        ->where('created_by', $user_id)
                        ->latest()
                        ->value('start_time');
                } elseif (!is_null($appointment->pharmacy_id)) {
                    if (!is_null($appointment->pharmacy) && !is_null($appointment->pharmacy->location)) {
                        $locationName = $appointment->pharmacy->location->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->pharmacy_id)
                        ->where('created_by', $user_id)
                        ->latest()
                        ->value('start_time');
                }

                // Add location name to the appointment object
                $appointment->location_name = $locationName;
                $appointment->lastsalescall = $lastsalescall;
            }

            // Add appointments to the array
            $appointmentsByDay[$currentDay->format('D, M d/Y')] = $appointments;

            // Move to the next day
            $currentDay->addDay();
        }


       // Determine the selected tab based on the provided appointment ID
        $selectedTab = null;
        foreach ($appointmentsByDay as $day => $appointments) {
            foreach ($appointments as $appointment) {
                if ($appointment->id == $appointmentz->id) {
                    $selectedTab = $day;
                    break 2; // Break both loops once the selected tab is found
                }
            }
        }

        // Pass the data to the view
        $data['pagetitle'] = "Planner information";
        $data['appointmentsByDay'] = $appointmentsByDay;
        $data['appointmentz'] = $appointmentz;
        $data['selectedTab'] = $selectedTab;
        return view('planner.plannerInfo', ['data' => $data]);
    }



    public function destroy_appointment($id)
    {
        try {
            $user = Appointment::findOrFail($id);
            $user->delete();

            return redirect()->route('planner.userCalender')->with('success', 'Appointment has been successfully deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting Appointment: ' . $e->getMessage());
        }
    }
    public function eventInfo($id)
    {

        $appointmentz = Appointment::with(['client', 'pharmacy', 'facility'])
            ->findOrFail($id);


        $user_id = $appointmentz->user_id;

        // Get the start and end of the week of the selected appointment
        $weekStartDate = Carbon::parse($appointmentz->start_time)->startOfWeek();
        $weekEndDate = Carbon::parse($appointmentz->start_time)->endOfWeek();

        // Initialize an array to store appointments for each day
        $appointmentsByDay = [];

        // Loop through each day of the week
        $currentDay = clone $weekStartDate;
        while ($currentDay <= $weekEndDate) {
            // Retrieve appointments for the current day
            $appointments = Appointment::whereDate('start_time', $currentDay)
                ->where('user_id', $user_id)
                ->with(['facility.location', 'client.locations', 'pharmacy.location']) // Eager load location relationships
                ->get();

            // Modify each appointment to include location name
            // Modify each appointment to include location name
            foreach ($appointments as $appointment) {
                // Initialize location name variable
                $locationName = null;
                $lastsalescall = null;

                if (!is_null($appointment->facility_id)) {
                    if (!is_null($appointment->facility) && !is_null($appointment->facility->location)) {
                        $locationName = $appointment->facility->location->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->facility_id)
                        ->where('created_by', $user_id)
                        ->latest()
                        ->value('start_time');

                } elseif (!is_null($appointment->client_id)) {
                    if (!is_null($appointment->client) && !is_null($appointment->client->locations)) {
                        $locationName = $appointment->client->locations->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->client_id)
                        ->where('created_by', $user_id)
                        ->latest()
                        ->value('start_time');
                } elseif (!is_null($appointment->pharmacy_id)) {
                    if (!is_null($appointment->pharmacy) && !is_null($appointment->pharmacy->location)) {
                        $locationName = $appointment->pharmacy->location->name;
                    }
                    $lastsalescall = SalesCall::where('client_id',$appointment->pharmacy_id)
                        ->where('created_by', $user_id)
                        ->latest()
                        ->value('start_time');
                }

                // Add location name to the appointment object
                $appointment->location_name = $locationName;
                $appointment->lastsalescall = $lastsalescall;
            }

            // Add appointments to the array
            $appointmentsByDay[$currentDay->format('D, M d/Y')] = $appointments;

            // Move to the next day
            $currentDay->addDay();
        }


        // Determine the selected tab based on the provided appointment ID
        $selectedTab = null;
        foreach ($appointmentsByDay as $day => $appointments) {
            foreach ($appointments as $appointment) {
                if ($appointment->id == $appointmentz->id) {
                    $selectedTab = $day;
                    break 2; // Break both loops once the selected tab is found
                }
            }
        }

        // Pass the data to the view
        $data['pagetitle'] = "Planner information";
        $data['appointmentsByDay'] = $appointmentsByDay;
        $data['appointmentz'] = $appointmentz;
        $data['selectedTab'] = $selectedTab;
        return view('planner.userEvent', ['data' => $data]);
    }


    public function reschedule($id)
    {
        $appointment = Appointment::with(['client', 'pharmacy', 'facility'])
            ->findOrFail($id);

        //return $appointment;
        $appointments = Appointment::all();
        $data['pagetitle'] = "Reschedule Planner";
        $data['appointment'] = $appointment;
        $data['appointments'] = $appointments;
        return view('planner.reschedule',['data' => $data]);

    }

    public function update_schedule(Request $request ,$id)
    {
        $appointment = Appointment::with(['client', 'pharmacy', 'facility'])
            ->findOrFail($id);

        $nextPlannedVisit = Carbon::parse($request->next_planned_visit);
        $appointment->comments = $request->notes;
        $appointment->start_time = $request->next_planned_visit;
        $appointment->finish_time = $nextPlannedVisit->addHour();
        $appointment->update();


        return redirect()->route('planner.eventInfo', ['id' => $id])->with('success', 'Schedule updated successfully');


    }

    public function lastCall($id)
    {
        $appointment = Appointment::with(['client', 'pharmacy', 'facility'])
            ->findOrFail($id);
        $user_id = $appointment->user_id;
        //return $user_id;

        if($appointment['client_id'] != null){
            $client_id = $appointment->client_id;
            $salescall = SalesCall::with(['client.locations', 'client.specialities', 'doublecallcolleague'])
                ->where('client_type','Doctor')
                ->where('client_id',$client_id)
                ->where('created_by',$user_id)
                ->latest()
                ->first();

            $data['title'] = "View Doctor Sales Call";
            $data['product_samples'] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $salescall->id)->get();
            $data['salescall'] = $salescall;

            return view('planner.lastDoctorCall',['data'=>$data]);
        }elseif ($appointment['facility_id'] != null) {
            $facility_id = $appointment->facility_id;
            $salescall = SalesCall::with(['client', 'salescalldetails'])
                ->where('client_type','Clinic')
                ->where('client_id',$facility_id)
                ->where('created_by',$user_id)
                ->latest()
                ->first();

            $salescalldetails = SalesCallDetail::where('sales_call_id', '=', $salescall->id)->get();
            $samples = [];

            foreach ($salescalldetails as $salescalldetail) {
                $samples[] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $salescall->id)->get();;
            }
            $data['title'] = "View Clinic Sales Call";
            $data['samples'] = $samples;
            $data['salescall'] = $salescall;
            return view('planner.lastClinicCall',['data'=>$data]);
        }elseif ($appointment['pharmacy_id'] != null){
            $pharmacy_id = $appointment->pharmacy_id;
            $salescall = SalesCall::with(['client', 'salescalldetails'])
                ->where('client_type','Pharmacy')
                ->where('client_id',$pharmacy_id)
                ->where('created_by',$user_id)
                ->latest()
                ->first();

            $pharmacy_id = SalesCall::where('id',$salescall->id)->value('client_id');
            $name = Pharmacy::where('id', $pharmacy_id)->value('name');

            $salescalldetails = SalesCallDetail::where('sales_call_id', '=', $salescall->id)->get();
            $samples = [];

            foreach ($salescalldetails as $salescalldetail) {
                $samples[] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $salescall->id)->get();;
            }
            $data['title'] = "View Clinic Sales Call";
            $data['samples'] = $samples;
            $data['facility_name'] = $name;
            $data['salescall'] = $salescall;
            return view('planner.lastPharmacyCall',['data'=>$data]);
        }
    }


    public function create_schedule()
    {
        $user = Auth::user();
        $clients = $user->clients()->with(['titles', 'specialities', 'locations'])->get()->toArray();

        $data['pagetitle'] = "Schedule An Appointment";
        $data['clients'] = $clients;
        //return $clients;
        return view('planner.create_schedule',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Add New Appointment";
        $data['pagetitle'] = $title;

        $filter_date = date('Y-m-d');
        $user = Auth::user();

        $clients = $user->clients()->with(['titles', 'specialities', 'locations'])->get()->toArray();
        $ids = array_column($clients , 'id');

        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            $locationIds = Location::where('territory_id', '=', $userTerritoryId)->pluck('id')->toArray();

            $data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId)
                ->orderBy('name')->get();

            /*
            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->whereIn('location_id', $locationIds)
                ->orderBy('last_name')->get();
            */
            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->wherein('id', $ids)
                ->orderBy('last_name')->get();


        } else {
            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->wherein('id', $ids)
                ->orderBy('last_name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        }




        $data['sales_call_ids'] = SalesCall::where('client_type', '=', 'Doctor')
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->where('created_by','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['appointments_ids'] = Appointment::where('client_id', '!=', null)
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('user_id','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['titles'] = Title::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();

        $data['users'] = User::orderBy('last_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        //return $clients;

        return view('appointments.create-appointment', ['data' => $data]);
    }

    public function list()
    {
        $title = "Reschedule Appointment";
        $data['pagetitle'] = $title;

        $filter_date = date('Y-m-d');
        $user = Auth::user();


        $data['appointments'] = Appointment::with('client')
            ->where('user_id', '=', $user->id)
            ->where('facility_id', '=', null)
            ->get();






        $data['start_time'] = date('Y-m-d H:i:s');
        //$data['specialities'] = Speciality::orderBy('name')->get();
        return view('appointments.edit-appointment', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createfacilityappointment()
    {
        $title = "Add New Facility Appointment";
        $data['pagetitle'] = $title;

        $filter_date = date('Y-m-d');
        $user = Auth::user();

        $clients = $user->facilities->toArray();
        $ids = array_column($clients , 'id');

		if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
			//$locationIds = Location::where('territory_id', '=', $userTerritoryId)->pluck('id')->toArray();
            $locationIds = Location::pluck('id')->toArray();
		} else  {
			$locationIds = Location::pluck('id')->toArray();
		}

        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
//            $data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId)
//                ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        } else {
            $data['newlocations'] = Location::orderBy('name')->get();
        }
        // Fetch clients whose locations are in the array of location IDs
        $data['facilities'] = Facility::with(['location'])
            //->whereIn('location_id', $locationIds)
            ->wherein('id', $ids)
            ->orderBy('name')->get();

        $data['sales_call_ids'] = SalesCall::where('client_type', '!=', 'Doctor')
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->pluck('client_id')
            ->toArray();

        //$data['titles'] = Title::orderBy('name')->get();
        //$data['specialities'] = Speciality::orderBy('name')->get();

        $data['users'] = User::orderBy('last_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        //$data['specialities'] = Speciality::orderBy('name')->get();
        return view('appointments.create-facility-appointment', ['data' => $data]);
    }

    public function createpharmacyappointment()
    {
        $title = "Add New Pharmacy Appointment";
        $data['pagetitle'] = $title;

        $filter_date = date('Y-m-d');
        $user = Auth::user();

        $clients = $user->pharmacies->toArray();
        $ids = array_column($clients , 'id');

        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            //$locationIds = Location::where('territory_id', '=', $userTerritoryId)->pluck('id')->toArray();
            $locationIds = Location::pluck('id')->toArray();
        } else  {
            $locationIds = Location::pluck('id')->toArray();
        }

        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
//            $data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId)
//                ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        } else {
            $data['newlocations'] = Location::orderBy('name')->get();
        }
        // Fetch clients whose locations are in the array of location IDs
        $data['pharmacies'] = Pharmacy::with(['location'])
            //->whereIn('location_id', $locationIds)
            ->wherein('id', $ids)
            ->orderBy('name')->get();

        $data['sales_call_ids'] = SalesCall::where('client_type', '!=', 'Doctor')
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->pluck('client_id')
            ->toArray();


        //$data['titles'] = Title::orderBy('name')->get();
        //$data['specialities'] = Speciality::orderBy('name')->get();

        $data['users'] = User::orderBy('last_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        //$data['specialities'] = Speciality::orderBy('name')->get();
        return view('appointments.create-pharmacy-appointment', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->get('newfirstname') != "") {
            $new_client = new Client();
            $new_client->first_name = $request->get('newfirstname');
            $new_client->last_name = $request->get('newlastname');
            //$new_client->class = $request->get('newclass');
            $new_client->title_id = $request->get('title_id');
            $new_client->speciality_id = $request->get('newspeciality');
            $new_client->location_id = $request->get('newlocation');
            $new_client->created_by = Auth::id();
            $new_client->save();
            $client_id_for_appointment = $new_client->id;

        } else {
            $client_id_for_appointment = $request->get('client_id');
        }

        $save_appointment = SalesCallController::save_appointment($request->get('next_planned_visit'), 'Comments', $client_id_for_appointment, Auth::id(), $request->get('next_planned_time'), $request->get('source'));

        toastr()->success('Appointment saved successfully');
        return back();
    }

    public function storefacilityappointment(Request $request)
    {
        if ($request->get('newfacilityname') != "") {
            $new_facility = new Facility();
            $new_facility->name = $request->get('newfacilityname');
            //$new_facility->class = $request->get('newclass');
            $new_facility->location_id = $request->get('newlocation');
            $new_facility->facility_type = $request->get('newtype');
            $new_facility->created_by = Auth::id();
            $new_facility->save();
            $client_id_for_appointment = $new_facility->id;
        } else {
            $client_id_for_appointment = $request->get('client_id');
        }

        $save_appointment = SalesCallController::save_appointment($request->get('next_planned_visit'), 'Comments', $client_id_for_appointment, Auth::id(), $request->get('next_planned_time'), $request->get('source'));

        toastr()->success('Appointment saved successfully');
        return back();
    }

    public function storepharmacyappointment(Request $request)
    {
        if ($request->get('newpharmacyname') != "") {
            $new_pharmacy = new Pharmacy();
            $new_pharmacy->name = $request->get('newpharmacyname');
            //$new_facility->class = $request->get('newclass');
            $new_pharmacy->location_id = $request->get('newlocation');
            $new_pharmacy->facility_type = $request->get('newtype');
            $new_pharmacy->created_by = Auth::id();
            $new_pharmacy->save();
            $client_id_for_appointment = $new_pharmacy->id;
        } else {
            $client_id_for_appointment = $request->get('client_id');
        }

        $save_appointment = SalesCallController::save_appointment($request->get('next_planned_visit'), 'Comments', $client_id_for_appointment, Auth::id(), $request->get('next_planned_time'), 'pharmacy');

        toastr()->success('Appointment saved successfully');
        return back();
    }
    /**
     * Display the specified resource.
     */
    public function ajaxUpdate(Request $request)
    {
        $appointment = Appointment::with('client')->findOrFail($request->appointment_id);
        $appointment->update($request->all());

        return response()->json(['appointment' => $appointment]);
    }
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment = Appointment::with('client')->findOrFail($request->appointment_id);

        $start = $request->get('next_planned_time');
        $day = $request->get('next_planned_visit');


        if (isset($day)) {

            if ($start == "none") {
                $start_time = $day." 08:00:00";
                $finish_time = $day." 08:30:00";
            } else {
                $start_time = $day." ".$start;

                // Create a DateTime object from the time string
                $dateTime = new DateTime($start);

                // Create a DateInterval object for 30 minutes
                $interval = new DateInterval('PT30M');

                // Add the interval to the DateTime object
                $dateTime->add($interval);

                // Get the updated time string
                $updatedTimeString = $dateTime->format('H:i:s');

                $finish_time = $day." ".$updatedTimeString;
            }



            $appointment->start_time = $start_time;
            $appointment->finish_time = $finish_time;
            $appointment->comments = "Rescheduled";
            $appointment->update();
            toastr()->success('Appointment saved successfully');
            return back();
        } else {
            toastr()->error('Error during Editing Appointment');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
