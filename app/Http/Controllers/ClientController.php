<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facility;
use App\Models\Location;
use App\Models\Pharmacy;
use App\Models\SalesCall;
use App\Models\Speciality;
use App\Models\Title;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $clients = $user->clients;
        $data['userclients'] = $clients;

        $clients = Client::with(['specialities', 'locations'])
            ->orderBy('first_name', 'asc')
            ->get();

        $parents = []; // Initialize the array

        foreach ($clients as $client) {
            $facility_code = $client->facility_code;
            $facility_type = $client->facility_type;

            $facility = null;

            if ($facility_type === null) {
                $facility = null;
            } elseif ($facility_type == 'Clinic') {
                $facility = Facility::where('code', $facility_code)->value('name');
            } elseif ($facility_type == 'Pharmacy') {
                $facility = Pharmacy::where('code', $facility_code)->value('name');
            }

            // Assign the facility name to the client object
            $client->facility_name = $facility;

            // Optionally, add each client to the $parents array
            $parents[] = $client;
        }

        //return $parents;


        $data['allclients'] = $parents;
        return view('manage.clients.index', ['data' => $data]);
    }

    public function index_two()
    {
        $user = Auth::user();

        $clients = $user->clients;
        $data['userclients'] = $clients;

        $clients = Client::with(['specialities', 'locations'])
            ->orderBy('first_name', 'asc')
            ->get();

        $parents = []; // Initialize the array

        foreach ($clients as $client) {
            $facility_code = $client->facility_code;
            $facility_type = $client->facility_type;

            $facility = null;

            if ($facility_type === null) {
                $facility = null;
            } elseif ($facility_type == 'Clinic') {
                $facility = Facility::where('code', $facility_code)->value('name');
            } elseif ($facility_type == 'Pharmacy') {
                $facility = Pharmacy::where('code', $facility_code)->value('name');
            }

            // Assign the facility name to the client object
            $client->facility_name = $facility;

            // Optionally, add each client to the $parents array
            $parents[] = $client;
        }

        //return $parents;


        $data['allclients'] = $parents;
        return view('manage.clients.admin_index', ['data' => $data]);
    }

    /**
     * Display a listing of the resource.
     */
    public function personal_list()
    {
        $user = Auth::user();

        $clients = $user->clients;

        //dd($clients);

        $data['userclients'] = $clients;


        $data['jobsession'] = [
            1=>'B',
            2=>'B',
            3=>'B',
            4=>'B',
            5=>'B',
            6=>'B',
            7=>'B',
            8=>'B',
            9=>'B',
        ];


        $clients2 = $clients->toArray();
        $ids = array_column($clients2 , 'id');

        $allclients = $user->clients->unique();

        //dd($allclients);

        $fourDaysAgo = Carbon::now()->subDays(4);

        $data['sales_call_ids'] = SalesCall::where('client_type', '=', 'Doctor')
            ->where('created_by','=',$user->id)
            ->whereDate('created_at', '>=', $fourDaysAgo)
            ->pluck('client_id')
            ->toArray();

        $data['last_visit_days'] = SalesCall::select('client_id', DB::raw('MAX(created_at) as last_visit_day'))
            ->where('client_type', '=', 'Doctor')
            ->where('created_by', '=', $user->id)
            ->groupBy('client_id')
            ->get();
        $currentMonth = now()->startOfMonth();
        $data['visit_counts'] = SalesCall::select('client_id', DB::raw('COUNT(*) as visit_count'))
            ->where('client_type', '=', 'Doctor')
            ->where('created_by', '=', $user->id)
            ->where('created_at', '>=', $currentMonth)
            ->groupBy('client_id')
            ->get();

        $data['allclients'] = $allclients;
        $data['classes'] = ['A', 'B'];
        $data['specialities'] = Speciality::orderBy('name')->get();
        return view('manage.clients.personal_list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manage.clients.create');
    }

    public function create_two()
    {
        $location = Location::orderBy('name')->get();
        $speciality = Speciality::all();
        $title = Title::all();
        $data['location']  = $location;
        $data['speciality']  = $speciality;
        $data['title']  = $title;

        return view('manage.clients.create_two', ['data' => $data]);
    }

    public function edit_two($id)
    {
        $client = Client::find($id);
        $locations = Location::orderBy('name')->get();
        $speciality = Speciality::all();
        $title = Title::all();

        $data['location']  = $locations;
        $data['speciality']  = $speciality;
        $data['title']  = $title;
        $data['client']  = $client;

        //return $data;
        return view('manage.clients.edit_two',['data' => $data]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function store_clients(Request $request)
    {
        try {
            $validatedData  = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'speciality' => 'required|exists:specialities,id',
                'title' => 'required|exists:titles,id',
                'code'=>'required|string|max:255',
            ]);

            // Create new client
            $new_client = new Client();
            $new_client->first_name = $validatedData['first_name'];
            $new_client->last_name = $validatedData['last_name'];
            $new_client->title_id = $validatedData['title'];
            $new_client->code = $validatedData['code'];
            $new_client->category = $validatedData['category'];
            $new_client->location_id = $validatedData['location'];
            $new_client->speciality_id = $validatedData['speciality'];
            $new_client->save();

            return redirect()->route('client-users.index')->with('success', 'Client Created Successfully');
        }catch (QueryException $e){
            return redirect()->back()->with('error', 'Error: Database query failed');
        }catch (\Exception $e){
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

    }

    public function update_client(Request $request, $id)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|exists:locations,id',
            'speciality' => 'required|exists:specialities,id',
            'title' => 'required|exists:titles,id',
            'code'=>'required|string|max:255',
        ]);


        try {

            $client = Client::findOrFail($id);
            $client->code = $validatedData['code'];
            $client->first_name = $validatedData['first_name'];
            $client->last_name = $validatedData['last_name'];
            $client->title_id = $validatedData['title'];
            $client->category = $validatedData['category'];
            $client->location_id = $validatedData['location'];
            $client->speciality_id = $validatedData['speciality'];
            $client->save();

            return redirect()->route('clients.index_two')->with('success', 'Client Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating client: ' . $e->getMessage());
        }
    }

    public function destroy_client($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return redirect()->route('clients.index_two')->with('success', 'Client Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting client: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    /**
     * Update clients selected by a user.
     */
    public function updateuserclients2(Request $request)
    {
        $user = Auth::user();


        $clients = $user->clients;
        $clients2 = $clients->toArray();
        $ids = array_column($clients2 , 'id');

        $allclients = Client::wherein('id', $ids)
            ->pluck('id')->toArray();

        $result = array_diff($allclients, $request->client);

        $user->clients()->detach($result);

        foreach ($request->client as $client_id) {
            $user->clients()->attach($client_id);
        }

        $allclients = Client::with(['specialities', 'locations'])
            ->orderBy('first_name', 'asc')
            ->get();
        $data['allclients'] = $allclients;

        $clients = $user->clients;
        $data['userclients'] = $clients;
        //return view('manage.clients.index', ['data' => $data]);

        toastr()->success('Doctors successfully updated');
        return back();
    }


	public function updateUserClients(Request $request)
	{
		$user = Auth::user();

        if (isset($request->client)) {
            // Get the currently attached client IDs for the user
            $attachedClientIds = $user->clients()->pluck('id')->toArray();

            // Calculate the IDs to be detached (clients not present in the request)
            $clientsToDetach = array_diff($attachedClientIds, $request->client);

            // Detach the clients that are no longer selected
            if (!empty($clientsToDetach)) {
                $user->clients()->detach($clientsToDetach);
            }

            // Attach the newly selected clients
            $user->clients()->syncWithoutDetaching($request->client);

            $allClients = Client::with(['specialities', 'locations'])
                ->orderBy('first_name', 'asc')
                ->get();
            $data['allClients'] = $allClients;

            $userClients = $user->clients;
            $data['userClients'] = $userClients;
        } else {
            $user->clients()->detach();
            $userClients = $user->clients;
            $data['userClients'] = $userClients;
        }
		toastr()->success('Doctors successfully updated');
		return back()->with('data', $data);
	}

    //personalupdateuserclients
    /**
     * Update clients selected by a user.
     */
    public function personalupdateuserclients(Request $request)
    {
        $user = Auth::user();

        $user->clients()->updateExistingPivot(
            $request->client_id,
            ['class' => $request->class]
        );

        $client = Client::findOrFail($request->client_id);

        $client->speciality_id = $request->speciality_id;
        $client->update();

        session([$request->client_id => $request->class]);

        //$data['jobsession'] = session([$request->client_id => $request->class]);

        toastr()->success('Doctor successfully updated');
        return back();
    }
}
