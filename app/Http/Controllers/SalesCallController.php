<?php

namespace App\Http\Controllers;

use App\Exports\PharmacyUsersExport;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Facility;
use App\Models\GeneralUploads;
use App\Models\GPSRecord;
use App\Models\Location;
use App\Models\NewDoctor;
use App\Models\NewFacility;
use App\Models\Notification;
use App\Models\Pharmacy;
use App\Models\PobImage;
use App\Models\Product;
use App\Models\ProductSample;
use App\Models\RecordSales;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\SalesCallDetail;
use App\Models\SalesComment;
use App\Models\SampleBatch;
use App\Models\SampleSlip;
use App\Models\Speciality;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Title;
use App\Models\User;
use App\Models\UserSampleInventory;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;


class SalesCallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('filter_start_date') && $request->has('filter_end_date')) {
            $filter_start_date = $request->get('filter_start_date');
            $filter_end_date = $request->get('filter_end_date');
        } else {
            $filter_start_date = date('Y-m-d');
            $filter_end_date = date('Y-m-d');
        }

		$user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        $query = SalesCall::with(['facility', 'salescalldetails'])
        ->where('client_type', '=', 'Clinic')
        ->whereBetween('start_time', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
		->where('created_by', '=', $user->id)
        ->orderByDesc('id')->get();

        $data['pagetitle'] = 'Clinic Sales Calls List';
        $data['salescalls'] = $query;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;
        return view('salescalls.index', ['data' => $data]);
    }

    public function newPharmacyClinic()
    {

        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $locations = Location::all();
        $my_facilities = NewFacility::with('location')
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        $data['pagetitle'] = 'Post New Clinic or Pharmacy';
        $data['locations'] = $locations;
        $data['facilities'] = $my_facilities;

        return view('salescalls.new_pharmacy_clinic',  ['data' => $data]);

    }

    public function editNewDoctors($id)
    {
        $titles = Title::all();
        $clinics = Facility::all();
        $locations = Location::all();
        $specialities = Speciality::all();

        $my_doctor = NewDoctor::with('location')
            ->with('title')
            ->with('speciality')
            ->where('id',$id)
            ->first();

        $data['locations'] = $locations;
        $data['pagetitle'] = 'Edit New Doctor';
        $data['clinics'] = $clinics;
        $data['specialities'] = $specialities;
        $data['titles'] = $titles;
        $data['doctor'] = $my_doctor;

        return view('salescalls.edit_new_doctor',  ['data' => $data]);

    }

    public function createNewDoctors(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'doctor_id' => 'required',
                'title' => 'required|exists:titles,id',
                'code' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'speciality' => 'required|exists:specialities,id',
                'category' => 'required|string|max:255',
                'clinics' => 'required|exists:facilities,id'
            ]);


            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;


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

            $client_id = $new_client->id;
            $clinics_ids = $validatedData['clinics'];

            foreach ($clinics_ids as $facility_id) {
                $facility = Facility::findOrFail($facility_id);
                $facility->doctors()->attach($client_id);
            }


            $doctor = NewDoctor::findOrFail($validatedData['doctor_id']);
            $doctor->status= "Approved";
            $doctor->save();


            return redirect()->route('salescalls.admin_new_doctor')->with('success', 'Facility created successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }
    }

    // Sales Records
    public function record_sales()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
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

        $clients = $clinic->merge($pharmacy);

        $products = Product::all();

        // Users Recorded Sales latest
        $sales = RecordSales::where('user_id',$user_id)->latest()->get();

        $data['pagetitle'] = 'Record Sales';
        $data['clients'] = $clients;
        $data['products'] = $products;
        $data['sales'] = $sales;
        return view('salescalls.sales_record',  ['data' => $data]);
    }

    public function approve_sales()
    {

        $currentMonth = Carbon::now()->month;

        $sales = RecordSales::with('user')
                           ->where('status','Pending')
                           ->get();

        $sales_approved = RecordSales::where('status','Approved')
            ->whereMonth('created_at', $currentMonth)
            ->get();

        $data['pagetitle'] = 'Record Sales';
        $data['sales'] = $sales;
        $data['sales_approved'] = $sales_approved;
        return view('salescalls.approve-sales',  ['data' => $data]);

    }


    public function approve_new_sale($id)
    {
        try {
            $sale = RecordSales::findOrFail($id);
            $sale->status = "Approved";
            $sale->save();
            return redirect()->route('salescalls.approve-reps-sale')->with('success', 'Sales Approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Approve the sale: ' . $e->getMessage());
        }
    }

    public function destroy_new_sale($id)
    {
        try {
            $sale = RecordSales::findOrFail($id);
            $sale->status = "Rejected";
            $sale->save();
            return redirect()->route('salescalls.approve-reps-sale')->with('success', 'Sales Approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Approve the sale: ' . $e->getMessage());
        }
    }

    public function export_excel(Request $request)
    {
        try {
            // Retrieve table data from the request
            $tableData = $request->input('tableData');
            // Create Excel file using Laravel Excel
            return Excel::download(new PharmacyUsersExport($tableData), 'offbook_sales.xlsx');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the export process
            return back()->withError('Export failed: ' . $e->getMessage());
        }
    }


    public function edit_sales($id)
    {
        $sale = RecordSales::find($id);
        $user_id = $sale->user_id;
        $user = User::find($user_id);


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

        $clients = $clinic->merge($pharmacy);

        $products = Product::all();


        $data['pagetitle'] = 'Record Sales';
        $data['sale'] = $sale;
        $data['clients'] = $clients;
        $data['products'] = $products;
        $data['id'] = $id;
        //return $sale;
        return view('salescalls.edit_sale',  ['data' => $data]);

    }

    public function update_record_sale($id)
    {
        $sale = RecordSales::findOrFail($id);
        $sale->customer_code = request('client_code');
        $sale->customer_name = request('client_name');
        $sale->product_code = request('product_code');
        $sale->product_name = request('product_name');
        $sale->quantity = request('product_qty');
        $sale->date = request('date_sold');

        // Save the changes
        $sale->save();

        // Optionally, you can return a response indicating success or redirect to a specific page
        return redirect()->route('salescalls.approve-reps-sale')->with('success', 'Sale record updated successfully');

    }

    // Store SalesCall Comments
    public function sales_comment(Request $request,$user_id, $sales_call_id)
    {
        // Validate incoming request
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        // Create a new comment
        $comment = new SalesComment();
        $comment->sales_call_id = $sales_call_id;
        $comment->user_id = $user_id;
        $comment->comment = $request->comment;
        $comment->save();

        // Dispatch notification
        $notification = new Notification([
            'user_id' => $user_id,
            'type' => 'SalesCall',
            'notifiable_id' => $sales_call_id,
            'notifiable_type' => get_class($comment), // or 'comment'
            'data' => json_encode([
                'comment_id' => $comment->id,
                'message' => $request->comment,
                'route' => 'salescalls/' . $sales_call_id . '/show-hospital'
            ]),
        ]);
        $notification->save();

        // Return a response if needed
        return response()->json(['message' => 'Comment stored successfully'], 200);
    }

    // Store Records
    public function store_record_sale(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_name' => 'required|string|max:255',
                'product_code' => 'required|string|max:255',
                'client_code' => 'required|string|max:255',
                'client_name' => 'required|string|max:255',
                'product_qty' => 'required|numeric',
                'date_sold' => 'required'
            ]);


            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            RecordSales::create([
                'user_id' => $user_id,
                'customer_code' => $validatedData['client_code'],
                'customer_name' => $validatedData['client_name'],
                'product_code' => $validatedData['product_code'],
                'product_name' => $validatedData['product_name'],
                'quantity' => $validatedData['product_qty'],
                'date' => $validatedData['date_sold'],
                'status' =>"Pending",
            ]);

            return redirect()->route('salescalls.record-sale')->with('success', 'Sales Recorded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }
    }

    // Titles and Speciality
    public function titles()
    {
        $titles = Title::all();
        $data['pagetitle'] = 'Titles';
        $data['titles'] = $titles;
        return view('salescalls.titles',  ['data' => $data]);
    }


    // GPS loacation
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

            // Check if the current time is between 8:00 AM and 6:00 PM
            if ($now->hour >= 7 && $now->hour < 18) {
                // Check if the user has already sent their GPS location today
                $firstRecord = $user->gpsRecords()->whereDate('recorded_at', $now->toDateString())->where('gps_type','Start')->first();
                if ($firstRecord) {
                    return response()->json(['message' => 'GPS location already sent today'], 400);
                }
            } else {
                // If the current time is not between 8:00 AM and 6:00 PM, return an error response
                return response()->json(['message' => 'GPS location can only be sent between 8:00 AM and 6:00 PM'], 400);
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


    public function speciality()
    {
        $specialities = Speciality::all();
        $data['pagetitle'] = 'Specialities';
        $data['specialities'] = $specialities;
        return view('salescalls.speciality',  ['data' => $data]);
    }


    public function create_speciality()
    {
        $data['pagetitle'] = 'Titles';
        return view('salescalls.create_speciality',  ['data' => $data]);
    }

    public function create_title()
    {
        $data['pagetitle'] = 'Titles';
        return view('salescalls.create_title',  ['data' => $data]);
    }


    public function store_title(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create a new Title instance and store it in the database
            $title = new Title();
            $title->name = $validatedData['name'];
            $title->save();

            // Return a success response
           return redirect()->route('salescalls.titles')->with('success', 'Title created successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return redirect()->back()->with('error', 'Failed to create Title: ' . $e->getMessage());
        }
    }

    public function store_speciality(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create a new Title instance and store it in the database
            $title = new Speciality();
            $title->name = $validatedData['name'];
            $title->save();

            // Return a success response
            return redirect()->route('salescalls.speciality')->with('success', 'Speciality created successfully');
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return redirect()->back()->with('error', 'Failed to create Speciality: ' . $e->getMessage());
        }
    }


    public function destroy_title($id)
    {
        try {
            $title = Title::findOrFail($id);
            $title->delete();
            return redirect()->route('salescalls.titles')->with('success', 'Title Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Delete Title: ' . $e->getMessage());
        }
    }

    public function destroy_speciality($id)
    {
        try {
            $title = Speciality::findOrFail($id);
            $title->delete();
            return redirect()->route('salescalls.speciality')->with('success', 'Speciality Deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Delete Speciality: ' . $e->getMessage());
        }
    }


    public function edit_title($id)
    {
        $title = Title::find($id);

        $data['pagetitle'] = 'Edit Titles';
        $data['title'] = $title;
        return view('salescalls.edit_title', ['data'=>$data]);
    }

    public function edit_speciality($id)
    {
        $speciality = Speciality::find($id);

        $data['pagetitle'] = 'Edit Titles';
        $data['speciality'] = $speciality;
        return view('salescalls.edit_title', ['data'=>$data]);
    }

    public function update_title(Request $request, $id)
    {
        try {
            $title = Title::findOrFail($id);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $title->update($validatedData);
            return redirect()->route('salescalls.titles')->with('success', 'Title Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Update Title: ' . $e->getMessage());
        }
    }

    public function update_speciality(Request $request, $id)
    {
        try {
            $title = Title::findOrFail($id);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $title->update($validatedData);
            return redirect()->route('salescalls.speciality')->with('success', 'Speciality Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to Update Speciality: ' . $e->getMessage());
        }
    }


    // Gps Map
    public function gpsMap()
    {
        $user_id = 10;
        $current_date = date('Y-m-d');
        $interval = GPSRecord::where('user_id',$user_id)
                                ->where('gps_type','Interval')
                                 ->whereBetween('recorded_at', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                                ->get();
        $calls = GPSRecord::where('user_id',$user_id)
            ->where('gps_type','Calls')
            ->whereBetween('recorded_at', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
            ->get();
        $start = GPSRecord::where('user_id',$user_id)
            ->where('gps_type','Start')
            ->whereBetween('recorded_at', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
            ->first();
        $all = GPSRecord::where('user_id',$user_id)
            ->whereBetween('recorded_at', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
            ->get();
        //return $interval;
        $data['pagetitle'] = 'GPS Map';
        $data['interval'] = $interval;
        $data['calls'] = $calls;
        $data['start'] = $start;
        return view('salescalls.gpsMap', ['data'=>$data]);
    }





    public function destroyNewDoctors($id)
    {
        $doctor = NewDoctor::findOrFail($id);
        $doctor->status = "Rejected";
        $doctor->save();

        return redirect()->route('salescalls.admin_new_doctor')->with('success', 'Doctor Deleted successfully');
    }

    public function editNewPharmacyClinic($id)
    {

        $locations = Location::all();
        $my_facilities = NewFacility::with('location')
            ->where('id', $id)
            ->first();

        $data['pagetitle'] = 'Edit New Clinic or Pharmacy';
        $data['locations'] = $locations;
        $data['facility'] = $my_facilities;


        return view('salescalls.edit_new_pharmacy',  ['data' => $data]);

    }

    public function createNewPharmacyClinic(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'facility_id' => 'required',
                'code' => 'required|string|max:255',
                'facility' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'type' => 'nullable|string|max:100',
            ]);

            if( $validatedData['type']  == 'clinic'){
                // Create a new Facility instance with validated data
                $new_facility = new Facility();
                $new_facility->name = $validatedData['facility'];
                $new_facility->code = $validatedData['code'];
                $new_facility->location_id = $validatedData['location'];
                $new_facility->created_by = Auth::id();
                $new_facility->save();
            }elseif($validatedData['type']  == 'pharmacy'){
                // Create a new Facility instance with validated data
                $new_facility = new Pharmacy();
                $new_facility->name = $validatedData['facility'];
                $new_facility->code = $validatedData['code'];
                $new_facility->location_id = $validatedData['location'];
                $new_facility->created_by = Auth::id();
                $new_facility->save();
            }

            $facility= NewFacility::findOrFail($validatedData['facility_id']);
            $facility->status= "Approved";
            $facility->save();


            return redirect()->route('salescalls.admin_new_pharmacy')->with('success', 'Facility created successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }
    }

    public function destroyNewPharmacyClinic($id)
    {
        try {
            $facility= NewFacility::findOrFail($id);
            $facility->status= "Rejected";
            $facility->save();

            return redirect()->route('salescalls.admin_new_pharmacy')->with('success', 'Facility created successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }

    }


    public function newDoctor()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $titles = Title::all();
        $clincs = Facility::all();
        $locations = Location::all();
        $specialities = Speciality::all();
        $my_doctors = NewDoctor::with('location')
                      ->with('title')
                      ->with('speciality')
                      ->where('user_id', $user_id)
                      ->latest()
                      ->get();
        $data['locations'] = $locations;
        $data['pagetitle'] = 'Post New Doctor';
        $data['clinics'] = $clincs;
        $data['specialities'] = $specialities;
        $data['titles'] = $titles;
        $data['doctors'] = $my_doctors;

        return view('salescalls.new_doctors',  ['data' => $data]);
    }
    public function generalUploads()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;

        $myUploads = GeneralUploads::with('location')
                     ->where('user_id',$user_id)
                     ->latest()
                     ->take(10)
                     ->get();

        $locations = Location::all();
        $data['locations'] = $locations;
        $data['uploads'] = $myUploads;
        $data['pagetitle'] = 'General uploads';
        return view('salescalls.general_uploads',  ['data' => $data]);
    }

    public function pobUploads()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $myUploads = PobImage::where('user_id',$user_id)
            ->latest()
            ->take(10)
            ->get();

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

        $clients = $clinic->merge($pharmacy);

        $data['pagetitle'] = 'General uploads';
        $data['clients'] = $clients;
        $data['uploads'] = $myUploads;
        return view('POB.index',  ['data' => $data]);
    }

    public function overalGeneralUploads(Request $request)
    {
        if($request->has('filter_date') &&  $request->has('end_date'))
        {
            $filter_date = $request->get('filter_date');
            $end_date = $request->get('end_date');

        }else{
            $filter_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }


        $uploads = GeneralUploads::with('location')
            ->whereDate('created_at', '>=', $filter_date)
            ->whereDate('created_at', '<=', $end_date)
            ->latest()
            ->get();

        $data['pagetitle'] = 'General uploads';
        $data['filter_date'] = $filter_date;
        $data['end_date'] = $end_date ;
        $data['uploads'] = $uploads;
        return view('salescalls.admin_general_uploads',['data' => $data]);
    }

    public  function adminNewPharmacyClinic()
    {
        $newFacilities = NewFacility::with('location')
                                     ->with('user')
                                     ->where('status', 'Pending')
                                     ->latest()
                                     ->get();

        $data['pagetitle'] = 'New Pharmacies/Clinics List';
        $data['facilities'] = $newFacilities;
        return view('salescalls.admin_new_pharmacy_clinic',['data' => $data]);

    }

    public  function adminNewDoctors()
    {

        $newDoctors = NewDoctor::with('location')
            ->with('title')
            ->with('speciality')
            ->with('user')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        $data['pagetitle'] = 'New Doctors List';
        $data['doctors'] = $newDoctors;
        return view('salescalls.admin_new_doctor',['data' => $data]);

    }




    public function storeGeneralUploads(Request $request)
    {

        try {
            // Validation rules
            $validatedData = $request->validate([
                'customer_name' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'notes' => 'nullable|string|max:100',
                'image' => 'required|image|max:2048'
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Handle file upload to Cloudinary
            if ($request->hasFile('image')) {
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';

                $uploadedImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => $folder,
                    'transformation' => [
                        'width' => $width,
                        'quality' => $quality,
                        'fetch' => $fetch,
                        'crop' => $crop
                    ]
                ])->getSecurePath();

                // Create a new general upload instance
                $general = new GeneralUploads();
                $general->customer_name = $validatedData['customer_name'];
                $general->location_id = $validatedData['location'];
                $general->notes = $validatedData['notes'];
                $general->user_id = $user_id;
                $general->image_path = $uploadedImage;
                $general->save();

                // Return a success response
                return redirect()->back()->with('success', 'Uploaded Successful');
            }
            return redirect()->back()->with('error', 'No Image Uploaded');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }


    }



    public function pobsUploads(Request $request)
    {
        try {
            // Validation rules
            $validatedData = $request->validate([
                'client_name' => 'required|string|max:255',
                'client_code' => 'required|string|max:255',
                'notes' => 'nullable|string|max:100',
                'image' => 'required|image'
            ]);

            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Handle file upload to Cloudinary
            if ($request->hasFile('image')) {
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';

                $uploadedImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                    'folder' => $folder,
                    'transformation' => [
                        'width' => $width,
                        'quality' => $quality,
                        'fetch' => $fetch,
                        'crop' => $crop
                    ]
                ])->getSecurePath();

                // Create a new general upload instance
                $pob = new PobImage();
                $pob->customer_name = $validatedData['client_name'];
                $pob->customer_code = $validatedData['client_code'];
                $pob->notes = $validatedData['notes'];
                $pob->user_id = $user_id;
                $pob->image_source = "cloudinary";
                $pob->pob_image_url = $uploadedImage;
                $pob->save();

                // Return a success response
                return redirect()->back()->with('success', 'Uploaded Successful');
            }
            return redirect()->back()->with('error', 'No Image Uploaded');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }
    }


    public function doctor_store(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'title' => 'required|exists:titles,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'speciality' => 'required|exists:specialities,id',
                'clinics' => 'required|exists:facilities,id'
            ]);

            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Create a new Doctor instance
            $doctor = new NewDoctor();
            $doctor->title_id= $validatedData['title'];
            $doctor->first_name = $validatedData['first_name'];
            $doctor->last_name = $validatedData['last_name'];
            $doctor->location_id = $validatedData['location'];
            $doctor->speciality_id = $validatedData['speciality'];
            $doctor->user_id = $user_id;
            $doctor->clinics = json_encode($validatedData['clinics']);
            $doctor->category = $validatedData['category'];
            $doctor->status = "Pending";
            $doctor->save();

            return redirect()->back()->with('success', 'New Doctor Posted Successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store new Doctor: ' . $e->getMessage());
        }
    }


    public function facility_store(Request $request)
    {

        try {
            // Validation rules
            $validatedData = $request->validate([
                'facility_name' => 'required|string|max:255',
                'location' => 'required|exists:locations,id',
                'type' => 'nullable|string|max:100',
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Create a new Facility instance
            $facility = new NewFacility();
            $facility->facility_name = $validatedData['facility_name'];
            $facility->location_id = $validatedData['location'];
            $facility->user_id = $user_id;
            $facility->type = $validatedData['type'];
            $facility->status= "Pending";
            $facility->save();

            // Return a success response
            return redirect()->back()->with('success', 'Facility stored successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to store facility: ' . $e->getMessage());
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function indexdoctor(Request $request)
    {

        if ($request->has('filter_start_date') && $request->has('filter_end_date')) {
            $filter_start_date = $request->get('filter_start_date');
            $filter_end_date = $request->get('filter_end_date');
        } else {
            $filter_start_date = date('Y-m-d');
            $filter_end_date = date('Y-m-d');
        }

        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        $query = SalesCall::with(['client'])
            ->where('client_type', '=', 'Doctor')
            ->whereBetween('start_time', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
            ->where('created_by', '=', $user->id)
            ->orderByDesc('id')->get();

        $data['pagetitle'] = 'Doctors Sales Calls List';
        $data['salescalls'] = $query;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;

        return view('salescalls.index-doctor', ['data' => $data]);
    }

    /**
     * Display a listing of the resource.
     */
    public function indexpharmacy(Request $request)
    {
        if ($request->has('filter_start_date') && $request->has('filter_end_date')) {
            $filter_start_date = $request->get('filter_start_date');
            $filter_end_date = $request->get('filter_end_date');
        } else {
            $filter_start_date = date('Y-m-d');
            $filter_end_date = date('Y-m-d');
        }

		$user = Auth::user();

        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }

        $query = SalesCall::with(['pharmacy', 'salescalldetails'])
        ->where('client_type', '=', 'Pharmacy')
        ->whereBetween('start_time', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
		->where('created_by', '=', $user->id)
        ->orderByDesc('id')->get();
        $data['pagetitle'] = 'Pharmacy Sales Calls List';
        $data['salescalls'] = $query;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;

        return view('salescalls.index-pharmacy', ['data' => $data]);
    }

	public function indexroundtable(Request $request)
    {
        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
        }

		$user = Auth::user();

        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }

        $query = SalesCall::with(['client', 'salescalldetails'])
        ->where('client_type', '=', 'RoundTable')
        ->where('start_time', 'LIKE', $filter_date . '%')
		->where('created_by', '=', $user->id)
        ->orderByDesc('id')->get();
        $data['pagetitle'] = 'RoundTables Sales Calls List';
        $data['salescalls'] = $query;
        $data['filter_date'] = $filter_date;

        return view('salescalls.index-roundtable', ['data' => $data]);
    }

	public function indexcme(Request $request)
    {
        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
        }

		$user = Auth::user();

        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }

        // Doctor CME
        $query = SalesCall::with(['client', 'salescalldetails'])
        ->where('client_type', '=', 'CME')
        ->where('start_time', 'LIKE', $filter_date . '%')
		->where('created_by', '=', $user->id)
        ->orderByDesc('id')->get();

        // Clinic CME
        $query2 = SalesCall::with(['facility', 'salescalldetails'])
            ->where('client_type', '=', 'CME-C')
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('created_by', '=', $user->id)
            ->orderByDesc('id')->get();

        // Pharmacy CME
        $query3 = SalesCall::with(['pharmacy', 'salescalldetails'])
            ->where('client_type', '=', 'CME-P')
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('created_by', '=', $user->id)
            ->orderByDesc('id')->get();

        $data['pagetitle'] = 'CME Sales Calls List';
        $data['salescalls'] = $query;
        $data['clinics'] = $query2;
        $data['pharmacies'] = $query3;
        $data['filter_date'] = $filter_date;

        return view('salescalls.index-cme', ['data' => $data]);
    }

    public function createNew()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Check if the data is already cached
        $cacheKey = 'clinic_data_' . $userId;
        if (Cache::has($cacheKey)) {
            $pharmacyData = Cache::get($cacheKey);
            $months = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            $currentMonth = Carbon::now()->month;
            $data['months'] =$months;
            $data['currentMonth'] =$currentMonth;
            $data = $this->createSalesCall('Pharmacy');
            return view('salescalls.create-pharmacy',  ['data' => $data, 'productMetrics' =>  $pharmacyData, 'months' =>  $months, 'currentMonth' =>  $currentMonth]);
        }

        $productsByFacility = [];
        for ($month = 1; $month <= 12; $month++) {
            $currentYear = Carbon::now()->year;
            $user = User::find($userId);

            $quarterNumber = null;
            //Calculate the quarters
            if ($month >= 1 && $month <= 3) {
                $quarterNumber = 1;
            } elseif ($month >= 4 && $month <= 6) {
                $quarterNumber =  2;
            } elseif ($month >= 7 && $month <= 9) {
                $quarterNumber = 3;
            } elseif ($month >= 10 && $month <= 12) {
                $quarterNumber = 4;
            } else {
                // Invalid month number
                return null;
            }

            $pharmacies = $user->pharmacies()->get();

            $productIds = [];


            // Retrieve product IDs from pharmacies
            foreach ($pharmacies as $pharmacy) {
                $facilityProducts = [];

                $facilityProductIds= array_merge($productIds, json_decode($pharmacy->pivot->product_ids, true) ?? []);
                $customer_code =$pharmacy->code;

                foreach ($facilityProductIds as $productId) {
                    $month_in_words = Carbon::create(null, $month, 1)->format('F');
                    $month_in_words_lowercase = strtolower($month_in_words);
                    $product = Product::where('id', $productId)->first();
                    $product_name = Product::where('id', $productId)->value('name');
                    $product_code = Product::where('id', $productId)->value('code');
                    if ($product) {
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('product_code', $product_code)
                            ->where('customer_code',$customer_code)
                            ->whereMonth('date', $month)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

                        $targetIds = Targets::where('product_id', $productId)
                            ->where('year', $currentYear)
                            ->where('code',$customer_code)
                            ->where('user_id', $userId)
                            ->where('quarter',  $quarterNumber)
                            ->pluck('id');


                        // Get the total target for the current product ID
                        // Get the first record that matches the query criteria
                        $target = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month',$month_in_words_lowercase)
                            ->groupBy(['target_id', 'month'])
                            ->get()
                            ->sum('target');

                        // Fetch product price using the relationship
                        $productPrice = $product->price;

                        // Target value
                        $targetValue = $productPrice * $target;

                        // Achieved Value
                        $achievedValue = $productPrice * $totalQuantity;

                        // Percentage performance
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        $child = [
                            'id' =>$productId,
                            'product_code' => $product_code,
                            'customer_code' => $customer_code,
                            'target' => $target ?? 0,
                            'target_value' =>$targetValue ?? 0,
                            'achieved_value' => $achievedValue,
                            'sum_quantity' => $totalQuantity ?? 0,
                            'performance ' => $percentagePerformance,
                            'product' => $product->name,
                            'quantity' => $totalQuantity ?? 0,
                        ];

                        $facilityProducts[] = $child;
                    }
                }
                $productsByFacility[$pharmacy->code][$month] = $facilityProducts;
            }
        }
        // Cache the data for future use
        Cache::put($cacheKey, $productsByFacility, 1440);

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $currentMonth = Carbon::now()->month;
        $data['months'] =$months;
        $data['currentMonth'] =$currentMonth;

        $data = $this->createSalesCall('Pharmacy');
        return view('salescalls.create-pharmacy',  ['data' => $data, 'productMetrics' => $productsByFacility, 'months' =>  $months, 'currentMonth' =>  $currentMonth]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Check if the data is already cached
        $cacheKey = 'clinic_data_' . $userId;
        if (Cache::has($cacheKey)) {
            $productsByFacility = Cache::get($cacheKey);
            $productMetrics = $productsByFacility;
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $user = User::find($userId);
        //month
        $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));

        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];

        $quarterNumber = null;
        foreach ($quarters as $quarter => $data) {
            if (in_array($monthName , $data['months'])) {
                $quarterNumber = $quarter;
                break;
            }
        }

        $facilities = $user->facilities()->get();

        $doctorsInFacilities = [];
        foreach ($facilities as $facility) {
            // Get the doctors associated with the current facility
            $doctors = $facility->doctors()->with('locations', 'specialities')->get();

            // Iterate through each doctor
            foreach ($doctors as $doctor) {
                // Query the SalesCall table to get the last sales call record for this doctor
                $lastCall = SalesCall::where('Client_type', 'Doctor')
                    ->where('client_id', $doctor->id)
                    ->where('created_by',$userId)
                    ->latest('created_at')
                    ->value('created_at');

                // Add the last sales call record to the doctor object
                $doctor->last_call = $lastCall;

                // Store the doctors in the result array, indexed by facility id
                $doctorsInFacilities[$facility->id][] = $doctor;
            }
        }
        //return $doctorsInFacilities;

        $productIds = [];
        $productsByFacility = [];

        // Retrieve product IDs from facilities
        foreach ($facilities as $facility) {
            $facilityProducts = [];

            $facilityProductIds= array_merge($productIds, json_decode($facility->pivot->product_ids, true) ?? []);
            $customer_code = $facility->code;
            $customer_name = $facility->name;
            $customer_rmos = $facility->total_rmos;
            $total_call_rmos = SalesCall::where("client_type", "Clinic")
                ->where("created_by", $userId)
                ->where("client_id", $facility->id)
                ->pluck('id');

            $total_count = 0;

            foreach ($total_call_rmos as $call_rmo_id) {
                $count = SalesCallDetail::where('sales_call_id', $call_rmo_id)->count();
                $total_count += $count;
            }


            foreach ($facilityProductIds as $productId) {
                $month = strtolower(Carbon::now()->format('F'));
                $product = Product::where('id', $productId)->first();
                $product_name = Product::where('id', $productId)->value('name');
                $product_code = Product::where('id', $productId)->value('code');
                if ($product) {
                    $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $product_code)
                    ->where('customer_code',$customer_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                    $targetIds = Targets::where('product_id', $productId)
                    ->where('year', $currentYear)
                    ->where('code',$customer_code)
                    ->where('user_id', $userId)
                    ->where('quarter',  $quarterNumber)
                    ->pluck('id');


                    // Get the total target for the current product ID
                    // Get the first record that matches the query criteria
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->groupBy(['target_id', 'month'])
                        ->get()
                        ->sum('target');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    $child = [
                        'id' =>$productId,
                        'product_code' => $product_code,
                        'customer_code' => $customer_code,
                        'customer_name' => $customer_name,
                        'customer_rmos' =>  $customer_rmos,
                        'customer_call_rmos' =>  $total_count,
                        'target' => $totalTarget ?? 0,
                        'target_value' =>$targetValue ?? 0,
                        'achieved_value' => $achievedValue,
                        'sum_quantity' => $totalQuantity ?? 0,
                        'performance ' => $percentagePerformance,
                        'product' => $product->name,
                        'quantity' => $totalQuantity ?? 0,
                    ];

                    $facilityProducts[] = $child;
                }
            }
            $productsByFacility[$facility->code] = $facilityProducts;
        }
       //return $productsByFacility;
        // Cache the data for future use
        Cache::put($cacheKey, $productsByFacility, 1440);

        // Add the overall percentage to the $data array
        $data = $this->createSalesCall('Clinic');
        $productMetrics = $productsByFacility;

        return view('salescalls.create', ['data' => $data, 'productMetrics' => $productMetrics, 'doctors' => $doctorsInFacilities]);
    }

    public function createOld()
    {
        $user = Auth::user();
        $userId = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $user = User::find($userId);
        //month
        $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));

        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];

        $quarterNumber = null;
        foreach ($quarters as $quarter => $data) {
            if (in_array($monthName , $data['months'])) {
                $quarterNumber = $quarter;
                break;
            }
        }

        $facilities = $user->facilities()->get();

        $doctorsInFacilities = [];
        foreach ($facilities as $facility) {
            // Get the doctors associated with the current facility
            $doctors = $facility->doctors()->with('locations', 'specialities')->get();

            // Iterate through each doctor
            foreach ($doctors as $doctor) {
                // Query the SalesCall table to get the last sales call record for this doctor
                $lastCall = SalesCall::where('Client_type', 'Doctor')
                    ->where('client_id', $doctor->id)
                    ->where('created_by',$userId)
                    ->latest('created_at')
                    ->value('created_at');

                // Add the last sales call record to the doctor object
                $doctor->last_call = $lastCall;

                // Store the doctors in the result array, indexed by facility id
                $doctorsInFacilities[$facility->id][] = $doctor;
            }
        }
        //return $doctorsInFacilities;

        $productIds = [];
        $productsByFacility = [];

        // Retrieve product IDs from facilities
        foreach ($facilities as $facility) {
            $facilityProducts = [];

            $facilityProductIds= array_merge($productIds, json_decode($facility->pivot->product_ids, true) ?? []);
            $customer_code = $facility->code;
            $customer_name = $facility->name;
            $customer_rmos = $facility->total_rmos;
            $total_call_rmos = SalesCall::where("client_type", "Clinic")
                ->where("created_by", $userId)
                ->where("client_id", $facility->id)
                ->pluck('id');

            $total_count = 0;

            foreach ($total_call_rmos as $call_rmo_id) {
                $count = SalesCallDetail::where('sales_call_id', $call_rmo_id)->count();
                $total_count += $count;
            }


            foreach ($facilityProductIds as $productId) {
                $month = strtolower(Carbon::now()->format('F'));
                $product = Product::where('id', $productId)->first();
                $product_name = Product::where('id', $productId)->value('name');
                $product_code = Product::where('id', $productId)->value('code');
                if ($product) {
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $product_code)
                        ->where('customer_code',$customer_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    $targetIds = Targets::where('product_id', $productId)
                        ->where('year', $currentYear)
                        ->where('code',$customer_code)
                        ->where('user_id', $userId)
                        ->where('quarter',  $quarterNumber)
                        ->pluck('id');


                    // Get the total target for the current product ID
                    // Get the first record that matches the query criteria
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->groupBy(['target_id', 'month'])
                        ->get()
                        ->sum('target');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    $child = [
                        'id' =>$productId,
                        'product_code' => $product_code,
                        'customer_code' => $customer_code,
                        'customer_name' => $customer_name,
                        'customer_rmos' =>  $customer_rmos,
                        'customer_call_rmos' =>  $total_count,
                        'target' => $totalTarget ?? 0,
                        'target_value' =>$targetValue ?? 0,
                        'achieved_value' => $achievedValue,
                        'sum_quantity' => $totalQuantity ?? 0,
                        'performance ' => $percentagePerformance,
                        'product' => $product->name,
                        'quantity' => $totalQuantity ?? 0,
                    ];

                    $facilityProducts[] = $child;
                }
            }
            $productsByFacility[$facility->code] = $facilityProducts;
        }
        //return $productsByFacility;

        // Add the overall percentage to the $data array
        $data = $this->createSalesCall('Clinic');
        $productMetrics = $productsByFacility;

        return view('salescalls.create', ['data' => $data, 'productMetrics' => $productMetrics, 'doctors' => $doctorsInFacilities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createdoctor()
    {

        $data = $this->createSalesCall('Doctor');
        return view('salescalls.create-doctor', ['data' => $data]);
    }

    public function fetchReportData()
    {
        $userId = auth()->id();

// Get unique product codes from sales table for the given user
        $productCodes = Sale::where('user_id', $userId)
            ->distinct('product_code')
            ->pluck('product_code');

// Get the targets for the product codes
        $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
            ->whereIn('products.code', $productCodes)
            ->get()
            ->groupBy('products.code');

        $productMetrics = [];

        foreach ($targets as $productCode => $targetData) {
            foreach ($targetData as $target) {
                // Get the quantity sold for the product
                $quantitySold = Sale::where('user_id', $userId)
                    ->where('product_code', $target->code)
                    ->sum('quantity');

                // Fetch product details using relationships
                $product = Product::where('code', $target->code)->first();

                if ($product) {
                    // Calculate metrics
                    $targetValue = $product->price * $target->target;
                    $achievedValue = $product->price * $quantitySold;

                    // Calculate percentage performance
                    $percentagePerformance = ($achievedValue / $targetValue) * 100;

                    // Calculate variance
                    $variance = $quantitySold - $target->target;

                    // Create a data array for the product
                    $productMetrics[] = [
                        'product_code' => $target->code,
                        'product_name' => $product->name,
                        'target' => $target->target,
                        'quantity_sold' => $quantitySold,
                        'variance' => $variance,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
                }
            }
        }


        return view('salescalls.create', ['productMetrics' => $productMetrics]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createpharmacy()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Check if the data is already cached
        $cacheKey = 'pharmacy_data_' . $userId;
        if (Cache::has($cacheKey)) {
            $pharmacyData = Cache::get($cacheKey);
            $months = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];

            $currentMonth = Carbon::now()->month;
            $data['months'] =$months;
            $data['currentMonth'] =$currentMonth;
            $data = $this->createSalesCall('Pharmacy');
            return view('salescalls.create-pharmacy',  ['data' => $data, 'productMetrics' =>  $pharmacyData, 'months' =>  $months, 'currentMonth' =>  $currentMonth]);
        }else{
            $productsByFacility = null;
        }

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $currentMonth = Carbon::now()->month;
        $data['months'] =$months;
        $data['currentMonth'] =$currentMonth;

        $data = $this->createSalesCall('Pharmacy');
        return view('salescalls.create-pharmacy',  ['data' => $data, 'productMetrics' => $productsByFacility, 'months' =>  $months, 'currentMonth' =>  $currentMonth]);
    }
    public function createpharmacyOld()
    {
        $user = Auth::user();
        $userId = $user->id;


        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $user = User::find($userId);
        //month
        $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));

        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];

        $quarterNumber = null;
        foreach ($quarters as $quarter => $data) {
            if (in_array($monthName , $data['months'])) {
                $quarterNumber = $quarter;
                break;
            }
        }

        $pharmacies = $user->pharmacies()->get();

        $productIds = [];
        $productsByFacility = [];

       // Retrieve product IDs from pharmacies
        foreach ($pharmacies as $pharmacy) {
            $facilityProducts = [];

            $facilityProductIds= array_merge($productIds, json_decode($pharmacy->pivot->product_ids, true) ?? []);
            $customer_code =$pharmacy->code;

            foreach ($facilityProductIds as $productId) {
                $month = strtolower(Carbon::now()->format('F'));
                $product = Product::where('id', $productId)->first();
                $product_name = Product::where('id', $productId)->value('name');
                $product_code = Product::where('id', $productId)->value('code');
                if ($product) {
                    $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $product_code)
                    ->where('customer_code',$customer_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                    $targetIds = Targets::where('product_id', $productId)
                    ->where('year', $currentYear)
                    ->where('code',$customer_code)
                    ->where('user_id', $userId)
                    ->where('quarter',  $quarterNumber)
                    ->pluck('id');


                    // Get the total target for the current product ID
                    // Get the first record that matches the query criteria
                    $target = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->groupBy(['target_id', 'month'])
                        ->get()
                        ->sum('target');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $target;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    $child = [
                        'id' =>$productId,
                        'product_code' => $product_code,
                        'customer_code' => $customer_code,
                        'target' => $target ?? 0,
                        'target_value' =>$targetValue ?? 0,
                        'achieved_value' => $achievedValue,
                        'sum_quantity' => $totalQuantity ?? 0,
                        'performance ' => $percentagePerformance,
                        'product' => $product->name,
                        'quantity' => $totalQuantity ?? 0,
                    ];

                    $facilityProducts[] = $child;
                }
            }
            $productsByFacility[$pharmacy->code] = $facilityProducts;

        }
        $data = $this->createSalesCall('Pharmacy');
        return view('salescalls.create-pharmacy',  ['data' => $data, 'productMetrics' => $productsByFacility]);
    }

	public function createroundtable()
    {
        $title = "Add New Sales Call (RoundTable)";
        $data['pagetitle'] = $title;
        //$data['clients'] = Client::with('titles','specialities','location')->orderBy('last_name')->get();

        $filter_date = date('Y-m-d');
        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }

        $clients = $user->clients->unique()->toArray();
        $ids = array_column($clients , 'id');


        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            // Retrieve an array of location IDs
            $locationIds = Location::where('territory_id', '=', $userTerritoryId)->pluck('id')->toArray();

            //$data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId) ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();

            // Fetch clients whose locations are in the array of location IDs
            /*
            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->whereIn('location_id', $locationIds)
                ->orderBy('last_name')->get();
            */
            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->wherein('id', $ids)
                ->orderBy('first_name')->get();





        } else {

            $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
                ->wherein('id', $ids)
                ->orderBy('first_name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        }

        /*
         *
         $data['clients'] = Client::with(['titles', 'specialities', 'locations' => function ($query) use ($locationIds) {
            $query->whereIn('id', $locationIds);
        }])->orderBy('last_name')->get();

        $data['clients'] = Client::with(['titles', 'specialities', 'location' => function ($query) use ($userTerritoryId) {
            $query->where('territory_id', $userTerritoryId);
        }])->orderBy('last_name')->get();
        */


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

        $data['users'] = User::orderBy('first_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        //$data['specialities'] = Speciality::orderBy('name')->get();
        $data['products'] = Product::orderBy('name')->get();

        $data['location_check_setting'] = config('settings.enable_location_check');
        return view('salescalls.create-roundtable', ['data' => $data]);
    }

	public function createcme()
    {
        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }
        $title = "Add New Sales Call (CME)";
        $data['pagetitle'] = $title;
        $client_type = "Doctor";
        $filter_date = Carbon::today();

        $clients = $user->clients->unique()->toArray();
        $ids = array_column($clients , 'id');

        $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
            ->wherein('id', $ids)
            ->orderBy('last_name')->get();
        $data['clients2'] = $user->clients->unique();
        $data['sales_call_ids'] = SalesCall::where('client_type', '=', $client_type)
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->where('created_by','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['appointments_ids'] = Appointment::where('client_id', '!=', null)
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('user_id','=',$user->id)
            ->pluck('client_id')
            ->toArray();
        $data['users'] = User::orderBy('first_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        $data['specialities'] = Speciality::orderBy('name')->get();
//        $data['products'] = SampleBatch::with('product')
//            ->where('user_id','=',$user->id)
//            ->where('quantity_remaining', '>', 0)
//            ->get();

        $data['products'] = UserSampleInventory::with('product')
            ->where('user_id','=',$user->id)
            ->where('quantity', '>', 0)
            ->get();

        $data['titles'] = Title::orderBy('name')->get();
        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            //$data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId) ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        } else {
            $data['newlocations'] = Location::orderBy('name')->get();
        }

        $data['location_check_setting'] = config('settings.enable_location_check');
        return view('salescalls.create-cme', ['data' => $data]);
    }

    public function createcmeclinic()
    {
        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $title = "Add New Sales Call (CME)";
        $data['pagetitle'] = $title;
        $client_type = "Clinic";
        $filter_date = Carbon::today();

        $clients = $user->facilities->unique()->toArray();
        $ids = array_column($clients , 'id');

        $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
            ->wherein('id', $ids)
            ->orderBy('last_name')->get();
        $data['clients2'] = $user->facilities->unique();
        $data['sales_call_ids'] = SalesCall::where('client_type', '=', $client_type)
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->where('created_by','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['appointments_ids'] = Appointment::where('client_id', '!=', null)
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('user_id','=',$user->id)
            ->pluck('client_id')
            ->toArray();
        $data['users'] = User::orderBy('first_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        $data['specialities'] = Speciality::orderBy('name')->get();
//        $data['products'] = SampleBatch::with('product')
//            ->where('user_id','=',$user->id)
//            ->where('quantity_remaining', '>', 0)
//            ->get();

        $data['products'] = UserSampleInventory::with('product')
            ->where('user_id','=',$user->id)
            ->where('quantity', '>', 0)
            ->get();
        $data['titles'] = Title::orderBy('name')->get();
        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            //$data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId) ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        } else {
            $data['newlocations'] = Location::orderBy('name')->get();
        }

        $data['location_check_setting'] = config('settings.enable_location_check');
        return view('salescalls.create-cme-clinic', ['data' => $data]);
    }

    public function createcmepharmacy()
    {
        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $title = "Add New Sales Call (CME)";
        $data['pagetitle'] = $title;
        $client_type = "Doctor";
        $filter_date = Carbon::today();

        $clients = $user->pharmacies->unique()->toArray();
        $ids = array_column($clients , 'id');

        $data['clients'] = Client::with(['titles', 'specialities', 'locations'])
            ->wherein('id', $ids)
            ->orderBy('last_name')->get();
        $data['clients2'] = $user->pharmacies->unique();
        $data['sales_call_ids'] = SalesCall::where('client_type', '=', $client_type)
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->where('created_by','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['appointments_ids'] = Appointment::where('client_id', '!=', null)
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('user_id','=',$user->id)
            ->pluck('client_id')
            ->toArray();
        $data['users'] = User::orderBy('first_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        $data['specialities'] = Speciality::orderBy('name')->get();
//        $data['products'] = SampleBatch::with('product')
//            ->where('user_id','=',$user->id)
//            ->where('quantity_remaining', '>', 0)
//            ->get();

        $data['products'] = UserSampleInventory::with('product')
            ->where('user_id','=',$user->id)
            ->where('quantity', '>', 0)
            ->get();
        $data['titles'] = Title::orderBy('name')->get();
        if (!is_null($user->territory_id)) {
            $userTerritoryId = $user->territory_id;
            //$data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId) ->orderBy('name')->get();
            $data['newlocations'] = Location::orderBy('name')->get();
        } else {
            $data['newlocations'] = Location::orderBy('name')->get();
        }

        $data['location_check_setting'] = config('settings.enable_location_check');
        return view('salescalls.create-cme-pharmacy', ['data' => $data]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

		$user = Auth::user();
        $salescall = new SalesCall();
        $salescall->client_type = $request->get('client_type');
            if ($request->get('newfirstname') != "") {
                $new_client = new Client();
                $new_client->first_name = $request->get('newfirstname');
                $new_client->last_name = $request->get('newlastname');
                if ($new_client->first_name === null && $new_client->last_name !== null) {
                    $new_client->first_name = $new_client->last_name;
                } elseif ($new_client->last_name === null && $new_client->first_name !== null) {
                    $new_client->last_name = $new_client->first_name;
                }
                //$new_client->class = $request->get('newclass');
                $new_client->title_id = $request->get('title_id');
                $new_client->speciality_id = $request->get('newspeciality');
                $new_client->location_id = $request->get('newlocation');
                $new_client->created_by = Auth::id();
                $new_client->save();
                $salescall->client_id = $new_client->id;
                //$user->clients()->attach($new_client->id);


                $user->clients()->attach($new_client->id,
                    [
                        'class' => $request->newclass
                    ]
                );

                /*
                $user->clients()->updateExistingPivot(
                    $new_client->client_id,
                    ['class' => $request->newclass]
                );
                */

            } else {
                $salescall->client_id = $request->get('client_id');
            }

            $client_id_for_appointment = $salescall->client_id;

        $salescall->start_time = $request->get('start_time');;
        $salescall->end_time = date('Y-m-d H:i:s');
        $salescall->double_call_colleague = 1;
        $salescall->discussion_summary = $request->get('discussion_summary');
        $salescall->next_planned_visit = $request->get('next_planned_visit');
        $salescall->longitude = $request->get('longitude');
        $salescall->latitude = $request->get('latitude');
        $salescall->created_by = Auth::id();
        $salescall->save();

        $client_id = $request->get('client_id');
        $current_date = date('Y-m-d');

        // Get Appointment(s) for the current day and the specified client_id
        $appointments = Appointment::where('client_id', $client_id)
            ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
            ->get();

        // Update the status of each appointment to "completed"
        foreach ($appointments as $appointment) {
            $appointment->status = "completed";
            $appointment->save(); // Save the changes to the database
        }

        $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $client_id_for_appointment, Auth::id(), $request->get('next_planned_time'));

        $sales_call_id = $salescall->id;
        $sample_product_id_array = $request->get('product_id');
        $sample_product_qty_array = $request->get('quantity');
        foreach ($sample_product_id_array as $key => $product_id)
        {
            if (!is_null($product_id)) {
                $product_sample = new ProductSample();
				$product_sample->client_type = $request->get('client_type');
                $product_sample->salescall_or_detail_id = $sales_call_id;
                $product_sample->sample_batch_id = $product_id;
                $product_sample->product_id = $product_id;
                $product_sample->quantity = $sample_product_qty_array[$key];
                $product_sample->save();

                $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
            }
        }

        $salescall->image_source = 'cloudinary';
        $folder = 'cloudinary-speed';
        $width = '700';
        $quality = 'auto';
        $fetch = 'auto';
        $crop = 'scale';

        if ($request->hasFile('UploadSampleSlip')) {
            $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                'folder'         => $folder,
                'transformation' => [
                    'width'   => $width,
                    'quality' => $quality,
                    'fetch'   => $fetch,
                    'crop'    => $crop
                ]
            ])->getSecurePath();

            // Save the URL and image source in your SalesCall model
            $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
            $salescall->update();
        }

        if ($request->hasFile('UploadSampleSlip')) {
            $salescall->addMediaFromRequest('UploadSampleSlip')
                ->toMediaCollection('sample_slip');
            $salescall->update();
        }



        if ($salescall) {
            $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');

            // Check if latitude and longitude are not empty
            if ($latitude !== null && $longitude !== null) {
                // Save the Gps records
                $client = Client::where('id', $salescall->client_id)->first();

                if ($client) {
                    $client_name = $client->first_name . ' ' . $client->last_name;
                } else {
                    // Handle case where client is not found
                    $client_name = 'Unknown'; // Or any other default value
                }
                $now = Carbon::now();
                GPSRecord::create([
                    'user_id' => Auth::id(),
                    'gps_type' => 'Calls',
                    'client_type' => "Doctor",
                    'client_id' => $salescall->client_id,
                    'Client_name' => $client_name,
                    'start_time' => $request->get('start_time'),
                    'end_time' => date('Y-m-d H:i:s'),
                    'latitude' => $request->get('latitude'),
                    'longitude' => $request->get('longitude'),
                    'recorded_at' => $now,
                ]);

            } else {
                toastr()->error('Latitude or longitude is empty. GPS record not saved.');
            }
        } else {
            toastr()->error('Error: Unable to retrieve sales call information.');
        }

        toastr()->success('Sales call saved successfully');
        return redirect()->route('home');
    }

    public function storehospital(Request $request)
    {
        $user = Auth::user();
        if ($request->input('action') == "store_hospital_submit") {


            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Facility();
                $new_facility->name = $request->get('newfacilityname');
                //$new_facility->class = $request->get('newclass');
                $new_facility->code = $request->get('code');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->facilities()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->pharmacy_order_booked = $request->get('order_booked');
            $salescall->pharmacy_reasons_for_not_booking = $request->get('ReasonsForNotBooking');
            if (!is_null($request->get('next_planned_visit'))) {
                $salescall->next_planned_visit = $request->get('next_planned_visit');
            }
            $salescall->created_by = Auth::id();

            //return $salescall;
            $salescall->save();

            $sales_call_id = $salescall->id;

            //Save details of first doctor
            $salescalldetails = new SalesCallDetail();
            $salescalldetails->sales_call_id  = $sales_call_id;
            $salescalldetails->first_name = $request->get('first_name');
            $salescalldetails->last_name = $request->get('last_name');
            $salescalldetails->speciality_id  = $request->get('speciality_id');
            $salescalldetails->title_id  = $request->get('title_id');
            $salescalldetails->double_call_colleague = 1;
            $salescalldetails->discussion_summary = $request->get('discussion_summary');
            $salescalldetails->longitude = $request->get('longitude');
            $salescalldetails->latitude = $request->get('latitude');
            $salescalldetails->created_by = Auth::id();
            $salescalldetails->save();

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('facility_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }

            if (!is_null($request->get('next_planned_visit'))) {
                $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "facility");
            }

            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->sales_call_detail_id = $salescalldetails_id;
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            if ($request->hasFile('UploadSampleSlip')) {
                $sample_slip = new SampleSlip();
                $sample_slip->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                try {
                    $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                        'folder'         => $folder,
                        'transformation' => [
                            'width'   => $width,
                            'quality' => $quality,
                            'fetch'   => $fetch,
                            'crop'    => $crop
                        ]
                    ])->getSecurePath();

                    // Save the URL and image source in your SampleSlip model
                    $sample_slip->user_id = Auth::id();
                    $sample_slip->sales_call_id = $sales_call_id;
                    $sample_slip->sales_call_detail_id = $salescalldetails_id;
                    $sample_slip->sample_slip_image_url = $uploadedSampleSlip;
                    $sample_slip->save();


                } catch (\Exception $e) {
                    // Handle any errors that occur during the upload process
                    toastr()->error('Failed to upload sample slip.');
                }
            } else {
                // Handle the case where no file was uploaded or the file is invalid
                //toastr()->error('Error: No valid file uploaded.');
            }


            if ($request->hasFile('UploadOrder')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedOrder = Cloudinary::upload($request->file('UploadOrder')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->pob_image_url = $uploadedOrder; // Assuming you have an image_url field
                $salescall->pharmacy_order_booked = 'Yes';
                $salescall->update();
            }
            // Save the Gps records
            if ($salescall) {
                $latitude = $request->get('latitude');
                $longitude = $request->get('longitude');

                // Check if latitude and longitude are not empty
                if ($latitude !== null && $longitude !== null) {
                    // Save the Gps records
                    $client_name = Facility::where('id', $salescall->client_id)->value('name');
                    $now = Carbon::now();
                    GPSRecord::create([
                        'user_id' => Auth::id(),
                        'gps_type' => 'Calls',
                        'client_type' => "Clinic",
                        'client_id' => $salescall->client_id,
                        'Client_name' => $client_name,
                        'start_time' => $request->get('start_time'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'recorded_at' => $now,
                    ]);

                } else {
                    toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                }
            } else {
                toastr()->error('Error: Unable to retrieve sales call information.');
            }

            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "finalize_hospital_submit") {
            $sales_call_id = $request->get('sales_call_id');

            $salescall = SalesCall::find($sales_call_id);
            $salescall->end_time = date('Y-m-d H:i:s');
            if (!is_null($request->get('next_planned_visit'))) {
                $salescall->next_planned_visit = $request->get('next_planned_visit');
            }
            $salescall->save();

            //Save details of doctor
            $salescalldetails = new SalesCallDetail();
            $salescalldetails->sales_call_id  = $sales_call_id;
            $salescalldetails->first_name = $request->get('first_name');
            $salescalldetails->last_name = $request->get('last_name');
            $salescalldetails->speciality_id  = $request->get('speciality_id');
            $salescalldetails->title_id  = $request->get('title_id');
            $salescalldetails->double_call_colleague = 1;
            $salescalldetails->discussion_summary = $request->get('discussion_summary');
            $salescalldetails->longitude = $request->get('longitude');
            $salescalldetails->latitude = $request->get('latitude');
            $salescalldetails->created_by = Auth::id();
            $salescalldetails->save();

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('facility_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }

            if (!is_null($request->get('next_planned_visit'))) {
                $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $request->get('client_id'), Auth::id(), $request->get('next_planned_time'), "facility");
            }


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->sales_call_detail_id = $salescalldetails_id;
                    $product_sample->save();
                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }


            if ($request->hasFile('UploadSampleSlip')) {
                $sample_slip = new SampleSlip();
                $sample_slip->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                try {
                    $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                        'folder'         => $folder,
                        'transformation' => [
                            'width'   => $width,
                            'quality' => $quality,
                            'fetch'   => $fetch,
                            'crop'    => $crop
                        ]
                    ])->getSecurePath();

                    // Save the URL and image source in your SampleSlip model
                    $sample_slip->user_id = Auth::id();
                    $sample_slip->sales_call_id = $sales_call_id;
                    $sample_slip->sales_call_detail_id = $salescalldetails_id;
                    $sample_slip->sample_slip_image_url = $uploadedSampleSlip;
                    $sample_slip->save();


                } catch (\Exception $e) {
                    // Handle any errors that occur during the upload process
                    toastr()->error('Failed to upload sample slip.');
                }
            } else {
                // Handle the case where no file was uploaded or the file is invalid
                //toastr()->error('Error: No valid file uploaded.');
            }

            if ($request->hasFile('UploadOrder')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedOrder = Cloudinary::upload($request->file('UploadOrder')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->pob_image_url = $uploadedOrder; // Assuming you have an image_url field
                $salescall->pharmacy_order_booked = 'Yes';
                $salescall->update();
            }

            if ($salescall) {
                $latitude = $request->get('latitude');
                $longitude = $request->get('longitude');

                // Check if latitude and longitude are not empty
                if ($latitude !== null && $longitude !== null) {
                    // Save the Gps records
                    $client_name = Facility::where('id', $salescall->client_id)->value('name');
                    $now = Carbon::now();
                    GPSRecord::create([
                        'user_id' => Auth::id(),
                        'gps_type' => 'Calls',
                        'client_type' => "Clinic",
                        'client_id' => $salescall->client_id,
                        'Client_name' => $client_name,
                        'start_time' => $request->get('start_time'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'recorded_at' => $now,
                    ]);

                } else {
                    toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                }
            } else {
                toastr()->error('Error: Unable to retrieve sales call information.');
            }


            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "continue_hospital_submit") {
            if ($request->has('sales_call_id')) {
                $sales_call_id = $request->get('sales_call_id');
                $client_facility_id = $request->get('client_id');
            } else {
                $salescall = new SalesCall();
                $salescall->client_type = $request->get('client_type');
                if ($request->get('newfacilityname') != "") {
                    $new_facility = new Facility();
                    $new_facility->name = $request->get('newfacilityname');
                    //$new_facility->class = $request->get('newclass');
                    $new_facility->location_id = $request->get('newlocation');
                    $new_facility->created_by = Auth::id();
                    $new_facility->save();
                    $client_facility_id = $new_facility->id;
                    $user->facilities()->attach($client_facility_id,
                        [
                            'class' => $request->newclass
                        ]);
                } else {
                    $client_facility_id = $request->get('client_id');
                }
                $salescall->client_id = $client_facility_id;
                $salescall->start_time = $request->get('start_time');;
                $salescall->longitude = $request->get('longitude');
                $salescall->latitude = $request->get('latitude');
                $salescall->pharmacy_order_booked = $request->get('order_booked');
                $salescall->pharmacy_reasons_for_not_booking = $request->get('ReasonsForNotBooking');
                if (!is_null($request->get('next_planned_visit'))) {
                    $salescall->next_planned_visit = $request->get('next_planned_visit');
                }
                $salescall->created_by = Auth::id();
                $salescall->save();
                $sales_call_id = $salescall->id;




                if ($salescall) {
                    $latitude = $request->get('latitude');
                    $longitude = $request->get('longitude');

                    // Check if latitude and longitude are not empty
                    if ($latitude !== null && $longitude !== null) {
                        // Save the Gps records
                        $client_name = Facility::where('id', $salescall->client_id)->value('name');
                        $now = Carbon::now();
                        GPSRecord::create([
                            'user_id' => Auth::id(),
                            'gps_type' => 'Calls',
                            'client_type' => "Clinic",
                            'client_id' => $salescall->client_id,
                            'Client_name' => $client_name,
                            'start_time' => $request->get('start_time'),
                            'end_time' => date('Y-m-d H:i:s'),
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'recorded_at' => $now,
                        ]);

                    } else {
                        toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                    }
                } else {
                    toastr()->error('Error: Unable to retrieve sales call information.');
                }
            }

            //Save details of doctor

            $salescalldetails = new SalesCallDetail();
            $salescalldetails->sales_call_id  = $sales_call_id;
            $salescalldetails->first_name = $request->get('first_name');
            $salescalldetails->last_name = $request->get('last_name');
            $salescalldetails->speciality_id  = $request->get('speciality_id');
            $salescalldetails->title_id  = $request->get('title_id');
            $salescalldetails->double_call_colleague = 1;
            $salescalldetails->discussion_summary = $request->get('discussion_summary');
            $salescalldetails->longitude = $request->get('longitude');
            $salescalldetails->latitude = $request->get('latitude');
            $salescalldetails->created_by = Auth::id();
            $salescalldetails->save();

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('facility_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }

            if (!is_null($request->get('next_planned_visit'))) {
                $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $client_facility_id, Auth::id(), $request->get('next_planned_time'), "facility");
            }


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->sales_call_detail_id = $salescalldetails_id;
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }


            if ($request->hasFile('UploadSampleSlip')) {
                $sample_slip = new SampleSlip();
                $sample_slip->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $sample_slip->user_id = Auth::id();
                $sample_slip->sales_call_id = $sales_call_id;
                $sample_slip->sales_call_detail_id = $salescalldetails_id;
                $sample_slip->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $sample_slip->save();
            }

            if ($request->hasFile('UploadOrder')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';

                $uploadedOrder = Cloudinary::upload($request->file('UploadOrder')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->pob_image_url = $uploadedOrder; // Assuming you have an image_url field
                $salescall->pharmacy_order_booked = 'Yes';
                $salescall->update();
            }






            $title = "Continue Adding Sales Call (Facility - Clinic)";
            $data['pagetitle'] = $title;
            $data['start_time'] = date('Y-m-d H:i:s');
            $data['returned_class'] = $request->get('myclass');
            $data['sales_call_id'] = $sales_call_id;

            if ($request->has('client_id')) {
                $client_id = $request->client_id;
            } else {
                $client_id = $client_facility_id;
            }
            $data['clients'] = Facility::where('id', '=', $client_id)->get();
            $data['users'] = User::orderBy('last_name')->get();
            $data['specialities'] = Speciality::orderBy('name')->get();
            $user = Auth::user();
            $userId = $user->id;
            $user = User::find($userId);
//            $data['products'] = SampleBatch::with('product')
//                ->where('user_id','=',$user->id)
//                ->where('quantity_remaining', '>', 0)
//                ->get();

            $data['products'] = UserSampleInventory::with('product')
                ->where('user_id','=',$user->id)
                ->where('quantity', '>', 0)
                ->get();

            $data['titles'] = Title::orderBy('name')->get();

            if (!is_null($user->territory_id)) {
                $userTerritoryId = $user->territory_id;
                $data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId)
                    ->orderBy('name')->get();
            } else {
                $data['newlocations'] = Location::orderBy('name')->get();
            }



            toastr()->success('Sales call saved successfully');
            return view('salescalls.create-finalize', ['data' => $data]);
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }

    }

    public function storepharmacy(Request $request)
    {
        $location_check_setting = config('settings.enable_location_check');
        $ft_duplicate_call_validator_toggle = config('settings.ft_duplicate_call_validator_toggle');

        if ($location_check_setting == "Off") {
            if ($ft_duplicate_call_validator_toggle == "On") {
                $validator = Validator::make($request->all(), [
                    'client_id' => 'required',
                    'start_time' => 'required|date',
                    // Add more rules based on your requirements
                ]);

                $validator->after(function ($validator) use ($request) {
                    // Check for duplicate entry
                    $duplicate = SalesCall::where([
                        'client_id' => $request->client_id,
                        'start_time' => $request->start_time,
                        // Add more fields as needed for uniqueness criteria
                    ])->exists();

                    if ($duplicate) {
                        $validator->errors()->add('duplicate_entry', 'This sales call already exists.');
                    }
                });
            }
        } else {
            if ($ft_duplicate_call_validator_toggle == "On") {
                $validator = Validator::make($request->all(), [
                    'client_id' => 'required',
                    'start_time' => 'required|date',
                    'longitude' => 'required',
                    'latitude' => 'required',
                    // Add more rules based on your requirements
                ]);

                $validator->after(function ($validator) use ($request) {
                    // Check for duplicate entry
                    $duplicate = SalesCall::where([
                        'client_id' => $request->client_id,
                        'start_time' => $request->start_time,
                        'longitude' => $request->longitude,
                        'latitude' => $request->latitude,
                        // Add more fields as needed for uniqueness criteria
                    ])->exists();

                    if ($duplicate) {
                        $validator->errors()->add('duplicate_entry', 'This sales call already exists.');
                    }
                });
            }
        }


        if ($ft_duplicate_call_validator_toggle == "On") {
            if ($validator->fails()) {
                toastr()->error('Duplicate Entry. You saved the record twice');
                return redirect()->route('home');
            }
        }

		$user = Auth::user();

        if ($request->input('action') == "store_pharmacy_submit") {
            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Pharmacy();
                $new_facility->name = $request->get('newfacilityname');
                $new_facility->code = $request->get('code');
                $new_facility->facility_type = 'Pharmacy';
                //$new_facility->class = $request->get('newclass');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->pharmacies()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');;
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->pharmacy_order_booked = $request->get('order_booked');
            $salescall->pharmacy_prescription_audit = $request->get('prescription_audited');
            $salescall->pharmacy_reasons_for_not_booking = $request->get('ReasonsForNotBooking');
            $salescall->pharmacy_reasons_for_not_auditing = $request->get('ReasonsForNotAuditing');
            $salescall->pharmacy_prescription_audit_notes = $request->get('pharmacy_prescription_audit_notes');
			$salescall->created_by = Auth::id();
            $salescall->save();

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('pharmacy_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }


            $sales_call_id = $salescall->id;

            //Save details of each pharmtech

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $speciality_array = $request->get('speciality_id');
            $discussions_array = $request->get('discussion_summary');

            foreach ($titles_array as $keyX => $pharTechX)
            {
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id  = $sales_call_id;
                $salescalldetails->title_id = $titles_array[$keyX];
                $salescalldetails->first_name = $fnames_array[$keyX];
                $salescalldetails->last_name = $lnames_array[$keyX];
                $salescalldetails->contact = $contacts_array[$keyX];
                $salescalldetails->discussion_summary = $discussions_array[$keyX];
                $salescalldetails->speciality_id = $speciality_array[$keyX];


                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "pharmacy");


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();
                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                   // $reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }


            /*
            if ($request->hasFile('UploadSampleSlip')) {
                $salescall->addMediaFromRequest('UploadSampleSlip')
                    ->toMediaCollection('sample_slip');
                $salescall->update();
            }

            if ($request->hasFile('UploadOrder')) {
                $salescall->addMediaFromRequest('UploadOrder')
                    ->toMediaCollection('order_booked');
                $salescall->pharmacy_order_booked = 'Yes';
                $salescall->update();
            } else {
                $salescall->pharmacy_order_booked = 'No';
                $salescall->update();
            }

            //UploadPrescription
            if ($request->hasFile('UploadPrescription')) {
                $salescall->addMediaFromRequest('UploadPrescription')
                    ->toMediaCollection('pharmacy_audit');
                $salescall->pharmacy_prescription_audit = 'Yes';
                $salescall->update();
            } else {
                $salescall->pharmacy_prescription_audit = 'No';
                $salescall->update();
            }
            */



            if ($request->hasFile('UploadSampleSlip')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $salescall->update();
            }

            if ($request->hasFile('UploadOrder')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedOrder = Cloudinary::upload($request->file('UploadOrder')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->pob_image_url = $uploadedOrder; // Assuming you have an image_url field
                $salescall->pharmacy_order_booked = 'Yes';
                $salescall->update();
            }

            if ($request->hasFile('UploadPrescription')) {
                $salescall->image_source = 'cloudinary';
                $folder = 'cloudinary-speed';
                $width = '700';
                $quality = 'auto';
                $fetch = 'auto';
                $crop = 'scale';
                $uploadedOrder = Cloudinary::upload($request->file('UploadPrescription')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->pxn_audit_image_url = $uploadedOrder; // Assuming you have an image_url field
                $salescall->pharmacy_prescription_audit = 'Yes';
                $salescall->update();
            }


            if ($salescall) {
                $latitude = $request->get('latitude');
                $longitude = $request->get('longitude');

                // Check if latitude and longitude are not empty
                if ($latitude !== null && $longitude !== null) {
                    // Save the Gps records
                    $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
                    $now = Carbon::now();
                    GPSRecord::create([
                        'user_id' => Auth::id(),
                        'gps_type' => 'Calls',
                        'client_type' => "Clinic",
                        'client_id' => $salescall->client_id,
                        'Client_name' => $client_name,
                        'start_time' => $request->get('start_time'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'recorded_at' => $now,
                    ]);

                } else {
                    toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                }
            } else {
                toastr()->error('Error: Unable to retrieve sales call information.');
            }

            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "finalize_pharmacy_submit") {
            $sales_call_id = $request->get('sales_call_id');

            $salescall = SalesCall::find($sales_call_id);
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->save();

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('pharmacy_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $speciality_array = $request->get('speciality_id');
            $discussions_array = $request->get('discussion_summary');

            if (is_array($titles_array)) {
                foreach ($titles_array as $keyX => $pharTechX) {
                    $salescalldetails = new SalesCallDetail();
                    $salescalldetails->sales_call_id = $sales_call_id;
                    $salescalldetails->title_id = $titles_array[$keyX];
                    $salescalldetails->first_name = $fnames_array[$keyX];
                    $salescalldetails->last_name = $lnames_array[$keyX];
                    $salescalldetails->contact = $contacts_array[$keyX];
                    $salescalldetails->speciality_id = $speciality_array[$keyX];
                    $salescalldetails->discussion_summary = $discussions_array[$keyX];

                    $salescalldetails->double_call_colleague = 1;
                    $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                    $salescalldetails->longitude = $request->get('longitude');
                    $salescalldetails->latitude = $request->get('latitude');
                    $salescalldetails->created_by = Auth::id();
                    $salescalldetails->save();
                }
            } else {
                // Handle the case where $titles_array is not an array
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id = $sales_call_id;
                $salescalldetails->title_id = $titles_array; // Assuming it's a single value
                $salescalldetails->first_name = $fnames_array; // Assuming it's a single value
                $salescalldetails->last_name = $lnames_array; // Assuming it's a single value
                $salescalldetails->contact = $contacts_array; // Assuming it's a single value
                $salescalldetails->speciality_id =$speciality_array; // Assuming it's a single value
                $salescalldetails->discussion_summary = $discussions_array; // Assuming it's a single value
                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $request->get('client_id'), Auth::id(), $request->get('next_planned_time'), "pharmacy");

            /*
            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $salescalldetails_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();
                }
            }
            */

            // Save the Gps records
            if ($salescall) {
                $latitude = $request->get('latitude');
                $longitude = $request->get('longitude');

                // Check if latitude and longitude are not empty
                if ($latitude !== null && $longitude !== null) {
                    // Save the Gps records
                    $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
                    $now = Carbon::now();
                    GPSRecord::create([
                        'user_id' => Auth::id(),
                        'gps_type' => 'Calls',
                        'client_type' => "Clinic",
                        'client_id' => $salescall->client_id,
                        'Client_name' => $client_name,
                        'start_time' => $request->get('start_time'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'recorded_at' => $now,
                    ]);

                } else {
                    toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                }
            } else {
                toastr()->error('Error: Unable to retrieve sales call information.');
            }
            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "continue_pharmacy_submit") {
            if ($request->has('sales_call_id')) {
                $sales_call_id = $request->get('sales_call_id');
            } else {
                $salescall = new SalesCall();
                $salescall->client_type = $request->get('client_type');
                if ($request->get('newfacilityname') != "") {
                    $new_facility = new Facility();
                    $new_facility->name = $request->get('newfacilityname');
                    //$new_facility->class = $request->get('newclass');
                    $new_facility->location_id = $request->get('newlocation');
                    $new_facility->created_by = Auth::id();
                    $new_facility->save();
                    $client_facility_id = $new_facility->id;
                    $user->facilities()->attach($client_facility_id,
                        [
                            'class' => $request->newclass
                        ]);
                } else {
                    $client_facility_id = $request->get('client_id');
                }
                $salescall->client_id = $client_facility_id;
                $salescall->start_time = $request->get('start_time');;
                $salescall->longitude = $request->get('longitude');
                $salescall->latitude = $request->get('latitude');
                if (!is_null($request->get('next_planned_visit'))) {
                    $salescall->next_planned_visit = $request->get('next_planned_visit');
                }
                $salescall->created_by = Auth::id();
                $salescall->save();
                $sales_call_id = $salescall->id;
            }

            $client_id = $request->get('client_id');
            $current_date = date('Y-m-d');

            // Get Appointment(s) for the current day and the specified client_id
            $appointments = Appointment::where('pharmacy_id', $client_id)
                ->whereBetween('start_time', [$current_date . ' 00:00:00', $current_date . ' 23:59:59'])
                ->get();

            // Update the status of each appointment to "completed"
            foreach ($appointments as $appointment) {
                $appointment->status = "completed";
                $appointment->save(); // Save the changes to the database
            }

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $speciality_array = $request->get('speciality_id');
            $discussions_array = $request->get('discussion_summary');

            if (is_array($titles_array)) {
                foreach ($titles_array as $keyX => $pharTechX) {
                    $salescalldetails = new SalesCallDetail();
                    $salescalldetails->sales_call_id = $sales_call_id;
                    $salescalldetails->title_id = $titles_array[$keyX];
                    $salescalldetails->first_name = $fnames_array[$keyX];
                    $salescalldetails->last_name = $lnames_array[$keyX];
                    $salescalldetails->contact = $contacts_array[$keyX];
                    $salescalldetails->speciality_id = $speciality_array[$keyX];
                    $salescalldetails->discussion_summary = $discussions_array[$keyX];

                    $salescalldetails->double_call_colleague = 1;
                    $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                    $salescalldetails->longitude = $request->get('longitude');
                    $salescalldetails->latitude = $request->get('latitude');
                    $salescalldetails->created_by = Auth::id();
                    $salescalldetails->save();
                }
            } else {
                // Handle the case where $titles_array is not an array
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id = $sales_call_id;
                $salescalldetails->title_id = $titles_array; // Assuming it's a single value
                $salescalldetails->first_name = $fnames_array; // Assuming it's a single value
                $salescalldetails->last_name = $lnames_array; // Assuming it's a single value
                $salescalldetails->contact = $contacts_array; // Assuming it's a single value
                $salescalldetails->speciality_id = $speciality_array; // Assuming it's a single value
                $salescalldetails->discussion_summary = $discussions_array; // Assuming it's a single value
                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }
            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $request->get('client_id'), Auth::id(), $request->get('next_planned_time'), "pharmacy");



            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');

            if (is_array($titles_array)) {
                foreach ($titles_array as $keyX => $pharTechX) {
                    $salescalldetails = new SalesCallDetail();
                    $salescalldetails->sales_call_id = $sales_call_id;
                    $salescalldetails->title_id = $titles_array[$keyX];
                    $salescalldetails->first_name = $fnames_array[$keyX];
                    $salescalldetails->last_name = $lnames_array[$keyX];
                    $salescalldetails->contact = $contacts_array[$keyX];
                    $salescalldetails->speciality_id = $speciality_array[$keyX];
                    $salescalldetails->discussion_summary = $discussions_array[$keyX];

                    $salescalldetails->double_call_colleague = 1;
                    $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                    $salescalldetails->longitude = $request->get('longitude');
                    $salescalldetails->latitude = $request->get('latitude');
                    $salescalldetails->created_by = Auth::id();
                    $salescalldetails->save();
                }
            } else {
                // Handle the case where $titles_array is not an array
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id = $sales_call_id;
                $salescalldetails->title_id = $titles_array; // Assuming it's a single value
                $salescalldetails->first_name = $fnames_array; // Assuming it's a single value
                $salescalldetails->last_name = $lnames_array; // Assuming it's a single value
                $salescalldetails->contact = $contacts_array; // Assuming it's a single value
                $salescalldetails->speciality_id = $speciality_array; // Assuming it's a single value
                $salescalldetails->discussion_summary = $discussions_array; // Assuming it's a single value
                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            $salescall->image_source = 'cloudinary';
            $folder = 'cloudinary-speed';
            $width = '700';
            $quality = 'auto';
            $fetch = 'auto';
            $crop = 'scale';

            if ($request->hasFile('UploadSampleSlip')) {
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $salescall->update();
            }




            $title = "Continue Adding Sales Call (Pharmacy)";
            $data['pagetitle'] = $title;
            $data['sales_call_id'] = $sales_call_id;
            $client_id = $request->get('client_id');
            $data['clients'] = Pharmacy::where('id', '=', $client_id)->get();
            $data['users'] = User::orderBy('last_name')->get();
            $data['specialities'] = Speciality::orderBy('name')->get();
            $data['products'] = Product::orderBy('name')->get();
            $data['titles'] = Title::orderBy('name')->get();
            if (!is_null($user->territory_id)) {
                $userTerritoryId = $user->territory_id;
                $data['newlocations'] = Location::where('territory_id', '=', $userTerritoryId)
                    ->orderBy('name')->get();
            } else {
                $data['newlocations'] = Location::orderBy('name')->get();
            }

            // Save the Gps records
            if ($salescall) {
                $latitude = $request->get('latitude');
                $longitude = $request->get('longitude');

                // Check if latitude and longitude are not empty
                if ($latitude !== null && $longitude !== null) {
                    // Save the Gps records
                    $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
                    $now = Carbon::now();
                    GPSRecord::create([
                        'user_id' => Auth::id(),
                        'gps_type' => 'Calls',
                        'client_type' => "Clinic",
                        'client_id' => $salescall->client_id,
                        'Client_name' => $client_name,
                        'start_time' => $request->get('start_time'),
                        'end_time' => date('Y-m-d H:i:s'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'recorded_at' => $now,
                    ]);

                } else {
                    toastr()->error('Latitude or longitude is empty. GPS record not saved.');
                }
            } else {
                toastr()->error('Error: Unable to retrieve sales call information.');
            }

            toastr()->success('Sales call saved successfully');
            return view('salescalls.create-finalize-pharmacy', ['data' => $data]);
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }

    }

	public function storeroundtable(Request $request)
    {
		$user = Auth::user();
        if ($request->input('action') == "store_pharmacy_submit") {
            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Facility();
                $new_facility->name = $request->get('newfacilityname');
                //$new_facility->class = $request->get('newclass');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->facilities()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');;
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->pharmacy_order_booked = $request->get('order_booked');
            $salescall->pharmacy_prescription_audit = $request->get('prescription_audited');
            $salescall->pharmacy_reasons_for_not_booking = $request->get('ReasonsForNotBooking');
            $salescall->pharmacy_reasons_for_not_auditing = $request->get('ReasonsForNotAuditing');
            $salescall->pharmacy_prescription_audit_notes = $request->get('pharmacy_prescription_audit_notes');
			$salescall->created_by = Auth::id();
            $salescall->save();

            $sales_call_id = $salescall->id;

            //Save details of each pharmtech

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $discussions_array = $request->get('discussion_summary');

            foreach ($titles_array as $keyX => $pharTechX)
            {
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id  = $sales_call_id;
                $salescalldetails->title_id = $titles_array[$keyX];
                $salescalldetails->first_name = $fnames_array[$keyX];
                $salescalldetails->last_name = $lnames_array[$keyX];
                $salescalldetails->contact = $contacts_array[$keyX];
                $salescalldetails->discussion_summary = $discussions_array[$keyX];

                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "pharmacy");


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            if($request->hasFile('UploadOrder')) {
                $salescall->addMediaFromRequest('UploadOrder')
                    ->toMediaCollection('thumbnail_web');
            }

            //UploadPrescription
            if($request->hasFile('UploadPrescription')) {
                $salescall->addMediaFromRequest('UploadPrescription')
                    ->toMediaCollection('pharmacy_audit');
            }


            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "finalize_pharmacy_submit") {
            $sales_call_id = $request->get('sales_call_id');

            $salescall = SalesCall::find($sales_call_id);
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->save();

            //Save details of doctor
            $salescalldetails = new SalesCallDetail();
            $salescalldetails->sales_call_id  = $sales_call_id;
            $salescalldetails->first_name = $request->get('first_name');
            $salescalldetails->last_name = $request->get('last_name');
            $salescalldetails->contact  = $request->get('contact');
            $salescalldetails->title_id  = $request->get('title_id');
            $salescalldetails->double_call_colleague = 1;
            $salescalldetails->discussion_summary = $request->get('discussion_summary');
            $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
            $salescalldetails->longitude = $request->get('longitude');
            $salescalldetails->latitude = $request->get('latitude');
            $salescalldetails->created_by = Auth::id();
            $salescalldetails->save();
            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $request->get('client_id'), Auth::id(), $request->get('next_planned_time'), "pharmacy");

            /*
            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $salescalldetails_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();
                }
            }
            */
            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } elseif ($request->input('action') == "continue_pharmacy_submit") {
            if ($request->has('sales_call_id')) {
                $sales_call_id = $request->get('sales_call_id');
            } else {
                $salescall = new SalesCall();
                $salescall->client_type = $request->get('client_type');
                $salescall->client_id = $request->get('client_id');
                $salescall->start_time = $request->get('start_time');;
                $salescall->longitude = $request->get('longitude');
                $salescall->latitude = $request->get('latitude');
				$salescall->created_by = Auth::id();
                $salescall->save();
                $sales_call_id = $salescall->id;
            }

            //Save details of doctor
            $salescalldetails = new SalesCallDetail();
            $salescalldetails->sales_call_id  = $sales_call_id;
            $salescalldetails->first_name = $request->get('first_name');
            $salescalldetails->last_name = $request->get('last_name');
            $salescalldetails->contact  = $request->get('contact');
            $salescalldetails->title_id  = $request->get('title_id');
            $salescalldetails->double_call_colleague = 1;
            $salescalldetails->discussion_summary = $request->get('discussion_summary');
            $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
            $salescalldetails->longitude = $request->get('longitude');
            $salescalldetails->latitude = $request->get('latitude');
            $salescalldetails->created_by = Auth::id();
            $salescalldetails->save();
            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $request->get('client_id'), Auth::id(), $request->get('next_planned_time'), "pharmacy");



            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }



            $title = "Continue Adding Sales Call (Pharmacy)";
            $data['pagetitle'] = $title;
            $data['sales_call_id'] = $sales_call_id;
            $client_id = $request->get('client_id');
            $data['clients'] = Facility::where('id', '=', $client_id)->get();
            $data['users'] = User::orderBy('last_name')->get();
            $data['specialities'] = Speciality::orderBy('name')->get();
            $data['products'] = Product::orderBy('name')->get();
            $data['titles'] = Title::orderBy('name')->get();

            toastr()->success('Sales call saved successfully');
            return view('salescalls.create-finalize-pharmacy', ['data' => $data]);
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }

    }

	public function storecme(Request $request)
    {
		$user = Auth::user();
        if ($request->input('action') == "store_cme_submit") {
            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Facility();
                $new_facility->name = $request->get('newfacilityname');
                $new_facility->code = $request->get('code');
                //$new_facility->class = $request->get('newclass');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->facilities()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');;
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->created_by = Auth::id();
            $salescall->save();

            $sales_call_id = $salescall->id;

            $salescall->image_source = 'cloudinary';
            $folder = 'cloudinary-speed';
            $width = '700';
            $quality = 'auto';
            $fetch = 'auto';
            $crop = 'scale';

            if ($request->hasFile('UploadSampleSlip')) {
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $salescall->update();
            }

            //Save details of each pharmtech

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $discussions_array = $request->get('discussion_summary');

            foreach ($titles_array as $keyX => $pharTechX)
            {
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id  = $sales_call_id;
                $salescalldetails->title_id = $titles_array[$keyX];
                $salescalldetails->first_name = $fnames_array[$keyX];
                $salescalldetails->last_name = $lnames_array[$keyX];
                $salescalldetails->contact = $contacts_array[$keyX];
                $salescalldetails->discussion_summary = $discussions_array[$keyX];

                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "pharmacy");


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            // Save the Gps records
            $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
            $now = Carbon::now();
            GPSRecord::create([
                'user_id' => Auth::id(),
                'gps_type' => 'Calls',
                'client_type' => "Doctor",
                'client_id' => $salescall->client_id,
                'Client_name' => $client_name,
                'start_time' => $request->get('start_time'),
                'end_time' => date('Y-m-d H:i:s'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'recorded_at' => $now,
            ]);



            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }

    }

    public function storecliniccme(Request $request)
    {
        $user = Auth::user();
        if ($request->input('action') == "store_cme_submit") {
            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Facility();
                $new_facility->name = $request->get('newfacilityname');
                $new_facility->code = $request->get('code');
                //$new_facility->class = $request->get('newclass');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->facilities()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');;
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->created_by = Auth::id();
            $salescall->save();

            $sales_call_id = $salescall->id;

            $salescall->image_source = 'cloudinary';
            $folder = 'cloudinary-speed';
            $width = '700';
            $quality = 'auto';
            $fetch = 'auto';
            $crop = 'scale';

            if ($request->hasFile('UploadSampleSlip')) {
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $salescall->update();
            }

            //Save details of each pharmtech

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $discussions_array = $request->get('discussion_summary');

            foreach ($titles_array as $keyX => $pharTechX)
            {
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id  = $sales_call_id;
                $salescalldetails->title_id = $titles_array[$keyX];
                $salescalldetails->first_name = $fnames_array[$keyX];
                $salescalldetails->last_name = $lnames_array[$keyX];
                $salescalldetails->contact = $contacts_array[$keyX];
                $salescalldetails->discussion_summary = $discussions_array[$keyX];

                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "pharmacy");


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                   // $product = SampleBatch::where('id',$product_id)->value('product_id');
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            // Save the Gps records
            $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
            $now = Carbon::now();
            GPSRecord::create([
                'user_id' => Auth::id(),
                'gps_type' => 'Calls',
                'client_type' => "Doctor",
                'client_id' => $salescall->client_id,
                'Client_name' => $client_name,
                'start_time' => $request->get('start_time'),
                'end_time' => date('Y-m-d H:i:s'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'recorded_at' => $now,
            ]);



            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }
    }
    public function storepharmacycme(Request $request)
    {
        $user = Auth::user();
        if ($request->input('action') == "store_cme_submit") {
            $salescall = new SalesCall();
            $salescall->client_type = $request->get('client_type');
            if ($request->get('newfacilityname') != "") {
                $new_facility = new Facility();
                $new_facility->name = $request->get('newfacilityname');
                $new_facility->code = $request->get('code');
                //$new_facility->class = $request->get('newclass');
                $new_facility->location_id = $request->get('newlocation');
                $new_facility->created_by = Auth::id();
                $new_facility->save();
                $salescall->client_id = $new_facility->id;
                $user->facilities()->attach($new_facility->id,
                    [
                        'class' => $request->newclass
                    ]);
            } else {
                $salescall->client_id = $request->get('client_id');
            }
            $sales_call_facility_id = $salescall->client_id;
            $salescall->start_time = $request->get('start_time');;
            $salescall->end_time = date('Y-m-d H:i:s');
            $salescall->longitude = $request->get('longitude');
            $salescall->latitude = $request->get('latitude');
            $salescall->created_by = Auth::id();
            $salescall->save();

            $sales_call_id = $salescall->id;

            $salescall->image_source = 'cloudinary';
            $folder = 'cloudinary-speed';
            $width = '700';
            $quality = 'auto';
            $fetch = 'auto';
            $crop = 'scale';

            if ($request->hasFile('UploadSampleSlip')) {
                $uploadedSampleSlip = Cloudinary::upload($request->file('UploadSampleSlip')->getRealPath(), [
                    'folder'         => $folder,
                    'transformation' => [
                        'width'   => $width,
                        'quality' => $quality,
                        'fetch'   => $fetch,
                        'crop'    => $crop
                    ]
                ])->getSecurePath();

                // Save the URL and image source in your SalesCall model
                $salescall->sample_slip_image_url = $uploadedSampleSlip; // Assuming you have an image_url field
                $salescall->update();
            }

            //Save details of each pharmtech

            $titles_array = $request->get('title_id');
            $fnames_array = $request->get('first_name');
            $lnames_array = $request->get('last_name');
            $contacts_array = $request->get('contact');
            $discussions_array = $request->get('discussion_summary');

            foreach ($titles_array as $keyX => $pharTechX)
            {
                $salescalldetails = new SalesCallDetail();
                $salescalldetails->sales_call_id  = $sales_call_id;
                $salescalldetails->title_id = $titles_array[$keyX];
                $salescalldetails->first_name = $fnames_array[$keyX];
                $salescalldetails->last_name = $lnames_array[$keyX];
                $salescalldetails->contact = $contacts_array[$keyX];
                $salescalldetails->discussion_summary = $discussions_array[$keyX];

                $salescalldetails->double_call_colleague = 1;
                $salescalldetails->next_planned_visit = $request->get('next_planned_visit');
                $salescalldetails->longitude = $request->get('longitude');
                $salescalldetails->latitude = $request->get('latitude');
                $salescalldetails->created_by = Auth::id();
                $salescalldetails->save();
            }


            $save_appointment = self::save_appointment($request->get('next_planned_visit'), 'Comments', $sales_call_facility_id, Auth::id(), $request->get('next_planned_time'), "pharmacy");


            $salescalldetails_id = $salescalldetails->id;

            $sample_product_id_array = $request->get('product_id');
            $sample_product_qty_array = $request->get('quantity');
            foreach ($sample_product_id_array as $key => $product_id)
            {
                if (!is_null($product_id)) {
                    //$product = SampleBatch::where('id',$product_id)->value('product_id');
                    $product_sample = new ProductSample();
                    $product_sample->client_type = $request->get('client_type');;
                    $product_sample->salescall_or_detail_id = $sales_call_id;
                    $product_sample->product_id = $product_id;
                    $product_sample->sample_batch_id = $product_id;
                    $product_sample->quantity = $sample_product_qty_array[$key];
                    $product_sample->save();

                    $this->updateSampleBatch($user->id, $product_id, $sample_product_qty_array[$key]);
                    //$reduce = self::updateSampleBatch($product_id, $sample_product_qty_array[$key]);
                }
            }

            // Save the Gps records
            $client_name = Pharmacy::where('id',$salescall->client_id)->value('name');
            $now = Carbon::now();
            GPSRecord::create([
                'user_id' => Auth::id(),
                'gps_type' => 'Calls',
                'client_type' => "Doctor",
                'client_id' => $salescall->client_id,
                'Client_name' => $client_name,
                'start_time' => $request->get('start_time'),
                'end_time' => date('Y-m-d H:i:s'),
                'latitude' => $request->get('latitude'),
                'longitude' => $request->get('longitude'),
                'recorded_at' => $now,
            ]);



            toastr()->success('Sales call saved successfully');
            return redirect()->route('home');
        } else {
            toastr()->error('Unknown Error');
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesCall $salescall)
    {
        $sales_call_id = $salescall->id;

        $salescall = SalesCall::with(['client.locations', 'client.specialities', 'doublecallcolleague'])
            ->find($sales_call_id);

        $data['title'] = "View Sales Call";
        $data['clients'] = Facility::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();
        $data['salescall'] = $salescall;

        //$sales_call_id = $salescall->id;

        $data['product_samples'] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $sales_call_id)->get();
        //return $data['product_samples'];

        return view('salescalls.show', ['data' => $data]);
    }

	/**
     * Display the specified resource.
     */
    public function showhospital(SalesCall $salescall)
    {
        $sales_call_id = $salescall->id;

        $salescall = SalesCall::with(['client', 'salescalldetails','sampleSlip','productSample'])
        ->find($sales_call_id);

        //return $salescall;
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $data['user_id'] = $user_id;
        $data['sales_call_id'] = $sales_call_id;


        $data['title'] = "View Sales Call";
        $data['clients'] = Facility::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();
        $data['salescall'] = $salescall;

        $salescalldetails = SalesCallDetail::where('sales_call_id', '=', $sales_call_id)->get();

        $samples = [];

        foreach ($salescalldetails as $salescalldetail) {
            $samples[] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $sales_call_id)->get();;
        }

        $comments = SalesComment::with('user')->where('sales_call_id',$sales_call_id)->get();

        //return $comments;

        $data['comments'] = $comments;


        $data['samples'] = $samples;

       //return $salescalldetails;

        return view('salescalls.show-hospital', ['data' => $data]);
    }

	/**
     * Display the specified resource.
     */
    public function showpharmacy(SalesCall $salescall)
    {
        $sales_call_id = $salescall->id;

        $salescall = SalesCall::with(['client', 'salescalldetails'])
        ->find($sales_call_id);
        $pharmacy_id = SalesCall::where('id',$sales_call_id)->value('client_id');
        $name = Pharmacy::where('id', $pharmacy_id)->value('name');

        $data['title'] = "View Sales Call";
        $data['clients'] = Pharmacy::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();
        $data['salescall'] = $salescall;
        $data['facility_name'] = $name;
        //return $pharmacy_id;

        $salescalldetails = SalesCallDetail::where('sales_call_id', '=', $sales_call_id)->get();

        $samples = [];

        foreach ($salescalldetails as $salescalldetail) {
            $samples[] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $sales_call_id)->get();
        }

        $data['samples'] = $samples;
        //return $salescall;

        return view('salescalls.show-pharmacy', ['data' => $data]);

    }


	/**
     * Display the specified resource.
     */
    public function showroundtable(SalesCall $salescall)
    {
        $sales_call_id = $salescall->id;

        $salescall = SalesCall::with(['client', 'doublecallcolleague'])
            ->find($sales_call_id);

        $data['title'] = "View Sales Call";
        $data['clients'] = Facility::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();
        $data['salescall'] = $salescall;

        //$sales_call_id = $salescall->id;

        $data['product_samples'] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $sales_call_id)->get();


        return view('salescalls.show-roundtable', ['data' => $data]);

    }

	/**
     * Display the specified resource.
     */
    public function showcme(SalesCall $salescall)
    {
        $sales_call_id = $salescall->id;

        $salescall = SalesCall::with(['client', 'salescalldetails'])
        ->find($sales_call_id);


        $data['title'] = "View CME Sales Call";
        $data['clients'] = Facility::orderBy('name')->get();
        $data['specialities'] = Speciality::orderBy('name')->get();
        $data['salescall'] = $salescall;

        $salescalldetails = SalesCallDetail::where('sales_call_id', '=', $sales_call_id)->get();

        $samples = [];

        foreach ($salescalldetails as $salescalldetail) {
            $samples[] = ProductSample::with(['product'])->where('salescall_or_detail_id', '=', $sales_call_id)->get();;
        }


        $data['samples'] = $samples;

		$mediaItems = $salescall->getMedia("*");
		$data['publicUrl'] = $salescall->getFirstMedia();
		//$data['publicFullUrl'] = $mediaItems[0]->getFullUrl();



        return view('salescalls.show-cme', ['data' => $data]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesCall $salescall)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesCall $salescall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesCall $salescall)
    {
        //
    }

    /**
     * Save Item In Calendar.
     */
    public static function save_appointment($day, $comments, $client_id, $user_id, $start="none", $source="client")
    {
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



			$appointment = new Appointment();
			$appointment->start_time = $start_time;
			$appointment->finish_time = $finish_time;
			$appointment->comments = $comments;
            if ($source == "client") {
                $appointment->client_id = $client_id;
            } elseif ($source == "pharmacy") {
                $appointment->pharmacy_id = $client_id;
            } else {
                $appointment->facility_id = $client_id;
            }
			$appointment->user_id = $user_id;
			$appointment->save();
			return 1;
        } else {
			return 0;
        }

    }

    /**
     * @return array
     */
    public function createSalesCall($client_type): array
    {

        $user = Auth::user();

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $title = "Add New Sales Call ($client_type)";
        $data['pagetitle'] = $title;

        if ($client_type == "Clinic") {
            $data['clients2'] = $user->facilities->unique();
        } elseif ($client_type == "Doctor") {
            $data['clients2'] = $user->clients->unique();
        } elseif ($client_type == "Pharmacy") {
            $data['clients2'] = $user->pharmacies->unique();
        }


        $data['users'] = User::orderBy('last_name')->get();
        $data['start_time'] = date('Y-m-d H:i:s');
        $data['specialities'] = Speciality::orderBy('name')->get();


        //$data['products'] = Product::orderBy('name')->get();
//        $data['products'] = SampleBatch::with('product')
//            ->where('user_id','=',$user->id)
//            ->where('quantity_remaining', '>', 0)
//            ->get();

        $data['products'] = UserSampleInventory::with('product')
            ->where('user_id','=',$user->id)
            ->where('quantity', '>', 0)
            ->get();


        $data['titles'] = Title::orderBy('name')->get();

        $filter_date = date('Y-m-d');
        $data['sales_call_ids'] = SalesCall::where('client_type', '=', $client_type)
            ->where('created_at', 'LIKE', $filter_date . '%')
            ->where('created_by','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['appointments_ids'] = Appointment::where('client_id', '!=', null)
            ->where('start_time', 'LIKE', $filter_date . '%')
            ->where('user_id','=',$user->id)
            ->pluck('client_id')
            ->toArray();

        $data['newlocations'] = Location::orderBy('name')->get();

        $data['location_check_setting'] = config('settings.enable_location_check');
        return $data;
    }

    public function updateSampleBatch2($sample_batch_id, $quantity_disbursed)
    {
        $sample_batch = SampleBatch::find($sample_batch_id);
        $sample_batch_remaining = $sample_batch->quantity_remaining;

        $new_value = $sample_batch_remaining - $quantity_disbursed;
        $sample_batch->quantity_remaining = $new_value;
        $sample_batch->update();
    }

    public function updateSampleBatch($user_id, $product_id, $quantity_disbursed)
    {
        $user_sample_inventory = UserSampleInventory::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($user_sample_inventory) {
            $sample_remaining = $user_sample_inventory->quantity;
            $new_value = $sample_remaining - $quantity_disbursed;
            $user_sample_inventory->quantity = $new_value;
            $user_sample_inventory->update();
        }
    }
}
