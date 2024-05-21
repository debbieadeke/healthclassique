<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\Location;
use App\Models\Product;
use App\Models\SalesCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($facility_type)
    {
        $user = Auth::user();


        $clients = $user->facilities;
        $data['userclients'] = $clients;

        //Show Spatie role name for current user

        //if role is user then only pick facilities with code not null
        if ($user->getRoleNames()[0] == 'user') {
            $allclients = Facility::with(['location'])
                ->where('facility_type', $facility_type)
                ->whereNotNull('code')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $allclients = Facility::with(['location'])
                ->where('facility_type', $facility_type)
                ->orderBy('name', 'asc')
                ->get();
        }


        $data['allclients'] = $allclients;
        $data['facility_type'] = $facility_type;

        if ($facility_type == "Clinic") {
            $data['pagecategory'] = "Clinics";
        } else {
            $data['pagecategory'] = "Pharmacies";
        }
        return view('manage.facilities.index', ['data' => $data]);
    }

    public function admin_page()
    {

        $facility_type = "Clinic";
        $user = Auth::user();


        $clients = $user->facilities;
        $data['userclients'] = $clients;

        //Show Spatie role name for current user

        //if role is user then only pick facilities with code not null
        if ($user->getRoleNames()[0] == 'user') {
            $allclients = Facility::with(['location'])
                ->where('facility_type', $facility_type)
                ->whereNotNull('code')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $allclients = Facility::with(['location'])
                ->where('facility_type', $facility_type)
                ->orderBy('name', 'asc')
                ->get();
        }


        $data['allclients'] = $allclients;
        $data['facility_type'] = $facility_type;

        if ($facility_type == "Clinic") {
            $data['pagecategory'] = "Clinics";
        } else {
            $data['pagecategory'] = "Pharmacies";
        }
        return view('manage.facilities.admin_index', ['data' => $data]);
    }

    /**
     * Display a listing of the resource.
     */
    public function personal_facilities_list($facility_type)
    {

        $user = Auth::user();
        $teamId = $user->team_id;

        $clients = $user->facilities->unique();


        $data['userclients'] = $clients;

        $clients2 = $clients->toArray();
        $ids = array_column($clients2 , 'id');



        //$allclients = $user->facilities;

        $allclients = $user->facilities->filter(function ($client) {
            return $client->facility_type === 'Clinic';
        });




        $data['sales_call_ids'] = SalesCall::where('client_type', '=',  $facility_type)
            ->where('created_by','=',$user->id)
            ->whereDate('created_at', '<=', now()->subDays(2)->setTime(0, 0, 0)->toDateTimeString())
            ->pluck('client_id')
            ->toArray();

        $data['last_visit_days'] = SalesCall::select('client_id', DB::raw('MAX(created_at) as last_visit_day'))
            ->where('client_type', '=',  $facility_type)
            ->where('created_by', '=', $user->id)
            ->groupBy('client_id')
            ->get();
        $currentMonth = now()->startOfMonth();
        $data['visit_counts'] = SalesCall::select('client_id', DB::raw('COUNT(*) as visit_count'))
            ->where('client_type', '=',  $facility_type)
            ->where('created_by', '=', $user->id)
            ->where('created_at', '>=', $currentMonth)
            ->groupBy('client_id')
            ->get();

        $data['products'] = Product::where('team_id',$teamId)->get();

        if ($facility_type == "Clinic") {
            $data['pagecategory'] = "Clinics";
        } else {
            $data['pagecategory'] = "Pharmacies";
        }

        $data['facility_type'] = $facility_type;


        $data['allclients'] = $allclients;
        $data['classes'] = ['A', 'B'];
        return view('manage.facilities.personal_list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $location = Location::orderBy('name')->get();
        $data['location']  = $location;
        return view('manage.facilities.create_facilities', ['data' => $data]);
    }


    public function manage_doctor($id)
    {

        $facility = Facility::findOrFail($id); // Find the facility
        $clients = Client::all()->sortBy('first_name');
        $data = [
            'facility' => $facility,
            'doctors' => $clients,
            'selectedDoctorIds' => $facility->doctors->pluck('id')->toArray(), // Get selected doctor IDs
            'pagetitle' => "Manage Doctors",
        ];

        return view('manage.facilities.clinics_doctors', ['data' => $data]);
    }


    public function facility_doctors(Request $request, $id)
    {
        $facility_type = "Clinic";
        $facility_id = $id;
        $doctor_ids = $request->input('doctors');

        // Find the facility
        $facility = Facility::findOrFail($facility_id);

        // Get the currently associated doctor IDs
        $currentDoctorIds = $facility->doctors->pluck('id')->toArray();

        if ($doctor_ids === null) {
            $doctor_ids = [];
        }

        // Compute the difference between $doctor_ids and $currentDoctorIds
        $newDoctorIds = array_diff($doctor_ids, $currentDoctorIds);
        $facility->doctors()->attach($newDoctorIds);

        // Remove unchecked doctors
        $uncheckedDoctorIds = array_diff($currentDoctorIds, $doctor_ids);
        $facility->doctors()->detach($uncheckedDoctorIds);

        return redirect()->route('managefacilities.admin_clinic',['facility_type' => $facility_type])->with('success','Doctors added successfully');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:facilities,code',
            'location' => 'required|exists:locations,id',
            'rmo' => 'required|string|max:255',
        ]);

        // Create a new Facility instance with validated data
        $new_facility = new Facility();
        $new_facility->name = $validatedData['name'];
        $new_facility->code = $validatedData['code'];
        $new_facility->location_id = $validatedData['location'];
        $new_facility->created_by = Auth::id();
        $new_facility->total_rmos = $validatedData['rmo'];
        $new_facility->save();

        $facility_type = 'Clinic';
        // Optionally, you can return a response indicating success
        return redirect()->route('facility-users.index', ['facility_type' => $facility_type])->with('success', 'Facility created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        //
    }
    public function edit_facility($id)
    {
        $clinic = Facility::find($id);
        $location = Location::orderBy('name')->get();
        $data['location']  = $location;
        $data['clinic'] = $clinic;

        return view('manage.facilities.edit_facilities', ['data' => $data]);
    }

    public function update_facility(Request $request, $id)
    {
        $pharmacy= Facility::findOrFail($id);
        $pharmacy->code = $request->input('code');
        $pharmacy->name = $request->input('name');
        $pharmacy->location_id = $request->input('location');
        $pharmacy->save();

        $facility_type = 'Clinic';


        return redirect()->route('managefacilities.admin_clinic', ['facility_type' => $facility_type])->with('success', 'Clinics updated successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $facility = Facility::findOrFail($request->id);
        $facility->delete();
        $facility_type = 'Clinic';
        return redirect()->route('managefacilities.admin_clinic', ['facility_type' => $facility_type])->with('success', 'Facility deleted successfully');
    }

	public function updateuserfacilities(Request $request)
	{
		$user = Auth::user();

        if (isset($request->client)) {
            $facility_type = $request->facility_type;

            /*
            // Get the currently attached client IDs for the user
            $attachedClientIds = $user->facilities()->pluck('id')->toArray();

            // Calculate the IDs to be detached (facilities not present in the request)
            $clientsToDetach = array_diff($request->client, $attachedClientIds);

            // Detach the clients that are no longer selected
            if (!empty($clientsToDetach)) {
                $user->facilities()->detach($clientsToDetach);
            }

            // Attach the newly selected clients
            $user->facilities()->syncWithoutDetaching($request->client);
            */

            // How can I detach all the facilities attached to the user and attach the newly selected ones?

            $user->facilities()->sync($request->client);

            $allClients = Facility::where('facility_type', $facility_type)
                ->orderBy('name', 'asc')
                ->get();
            $data['allClients'] = $allClients;

            $userclients = $user->facilities;
            $data['userclients'] = $userclients;
        } else {
            $user->facilities()->detach();
            $userclients = $user->facilities;
            $data['userclients'] = $userclients;
        }

        toastr()->success('Facilities successfully updated');
		return back()->with('data', $data);
	}

    public function personalupdateuserfacilities(Request $request)
    {
        $user = Auth::user();

        $user->facilities()->updateExistingPivot(
            $request->client_id,
            ['class' => $request->class, 'product_ids' => $request->products]
        );

        $client = Facility::findOrFail($request->client_id);

        $client->update();

        toastr()->success('Facility successfully updated');
        return back();
    }
}
