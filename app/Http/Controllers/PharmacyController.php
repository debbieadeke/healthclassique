<?php

namespace App\Http\Controllers;

use App\Exports\PharmacyUsersExport;
use App\Models\Facility;
use App\Models\Location;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\SalesCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($facility_type)
    {
        $user = Auth::user();

        //return $user;

        $clients = $user->pharmacies;
        $data['userclients'] = $clients;

        //if role is user then only pick pharmacies with code not null
        if ($user->getRoleNames()[0] == 'user') {
            $allclients = Pharmacy::with(['location'])
                ->where('facility_type', $facility_type)
                ->whereNotNull('code')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $allclients = Pharmacy::with(['location'])
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

        // Retrieve selected checkboxes from session
        $selectedCheckboxes = session('selectedCheckboxes', []);

        //return $selectedCheckboxes;
        return view('manage.pharmacies.index', ['data' => $data,'selectedCheckboxes'=>$selectedCheckboxes]);
    }


    public function admin_page()
    {

        $facility_type = "Pharmacy";
        $user = Auth::user();

        $clients = $user->pharmacies;
        $data['userclients'] = $clients;

        //if role is user then only pick pharmacies with code not null
        if ($user->getRoleNames()[0] == 'user') {
            $allclients = Pharmacy::with(['location'])
                ->where('facility_type', $facility_type)
                ->whereNotNull('code')
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $allclients = Pharmacy::with(['location'])
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

        // Retrieve selected checkboxes from session
        $selectedCheckboxes = session('selectedCheckboxes', []);

        //return $selectedCheckboxes;
        return view('manage.pharmacies.admin_index', ['data' => $data,'selectedCheckboxes'=>$selectedCheckboxes]);
    }

    public function export_excel(Request $request)
    {
        try {
            // Retrieve table data from the request
            $tableData = $request->input('tableData');

            // Create Excel file using Laravel Excel
            return Excel::download(new PharmacyUsersExport($tableData), 'pharmacy_users.xlsx');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the export process
            return back()->withError('Export failed: ' . $e->getMessage());
        }
    }

    public function storeSelectedCheckboxes(Request $request)
    {
        $selectedCheckboxes = $request->input('selectedCheckboxes', []);
        Session::put('selectedCheckboxes', $selectedCheckboxes);

        return response()->json(['message' => 'Selected checkboxes stored successfully.']);
    }

    /**
     * Display a listing of the resource.
     */
    public function personal_pharmacies_list($facility_type)
    {
        $user = Auth::user();
        $teamId = $user->team_id;

        $clients = $user->pharmacies->unique();


        $data['userclients'] = $clients;

        $clients2 = $clients->toArray();
        $ids = array_column($clients2 , 'id');



        $allclients = $user->pharmacies->unique();

        $data['sales_call_ids'] = SalesCall::where('client_type', '=', '$facility_type')
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
        return view('manage.pharmacies.personal_list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $location = Location::orderBy('name')->get();
        $data['location']  = $location;
        return view('manage.pharmacies.create_pharmacies', ['data' => $data]);
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
            'location' => 'required|exists:locations,id', // Assuming locations table exists
        ]);

        // Create a new Facility instance with validated data
        $new_facility = new Pharmacy();
        $new_facility->name = $validatedData['name'];
        $new_facility->code = $validatedData['code'];
        $new_facility->location_id = $validatedData['location'];
        $new_facility->created_by = Auth::id();
        $new_facility->save();

        $facility_type = 'Pharmacy';
        // Optionally, you can return a response indicating success
        return redirect()->route('pharmacy.index', ['facility_type' => $facility_type])->with('success', 'Facility created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pharmacy $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pharmacy $facility)
    {

    }
    // use id to get the editing pharmacy
    public function edit_pharmacy($id)
    {
        $pharmacy = Pharmacy::find($id);
        $location = Location::orderBy('name')->get();
        $data['location']  = $location;
        $data['pharmacy'] = $pharmacy;

        return view('manage.pharmacies.edit_pharmacy', ['data' => $data]);
    }

    public function update_pharmacy(Request $request, $id)
    {
        $pharmacy= Pharmacy::findOrFail($id);
        $pharmacy->code = $request->input('code');
        $pharmacy->name = $request->input('name');
        $pharmacy->location_id = $request->input('location');
        $pharmacy->save();

        $facility_type = 'Pharmacy';

        return redirect()->route('managepharmacies.admin_pharmacy', ['facility_type' => $facility_type])->with('success', 'Pharmacy updated successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pharmacy $facility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $pharmacy= Pharmacy::findOrFail($request->id);
        $pharmacy->delete();
        $facility_type = 'Pharmacy';
        return redirect()->route('managepharmacies.admin_pharmacy', ['facility_type' => $facility_type])->with('success', 'Pharmacy deleted successfully');
    }

	public function updateuserpharmacies(Request $request)
	{

        //dd($request);

		$user = Auth::user();


        if (isset($request->client)) {
            $facility_type = $request->facility_type;

            /*
            // Get the currently attached client IDs for the user
            $attachedClientIds = $user->pharmacies()->pluck('id')->toArray();

            dd($attachedClientIds);

            // Calculate the IDs to be detached (pharmacies not present in the request)
            $clientsToDetach = array_diff($request->client, $attachedClientIds);
            //return $clientsToDetach;


            //dd($attachedClientIds, $clientsToDetach);

            // Detach the clients that are no longer selected
            if (!empty($clientsToDetach)) {
                $user->pharmacies()->detach($clientsToDetach);
            }

            // Attach the newly selected clients
            $user->pharmacies()->syncWithoutDetaching($request->client);
            */

            // How can I detach all the pharmacies attached to the user and attach the newly selected ones?

            $user->pharmacies()->sync($request->client);





            $allClients = Pharmacy::where('facility_type', $facility_type)
                ->orderBy('name', 'asc')
                ->get();
            $data['allClients'] = $allClients;

            $userclients = $user->pharmacies;
            $data['userclients'] = $userclients;
        } else {
            $user->pharmacies()->detach();
            $userclients = $user->pharmacies;
            $data['userclients'] = $userclients;
        }

        toastr()->success('Facilities successfully updated');
		return back()->with('data', $data);
	}

    public function personalupdateuserpharmacies(Request $request)
    {
        $user = Auth::user();

        //dd($request);

        $user->pharmacies()->updateExistingPivot(
            $request->client_id,
            ['class' => $request->class, 'product_ids' => $request->products]
        );

        $client = Pharmacy::findOrFail($request->client_id);

        $client->update();

        toastr()->success('Pharmacy successfully updated');
        return back();
    }
}
