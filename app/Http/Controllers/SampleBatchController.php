<?php

namespace App\Http\Controllers;

use App\Models\ProductSample;
use App\Models\SalesCall;
use App\Models\SampleInventory;
use App\Models\SampleRequest;
use App\Models\User;
use App\Models\Product;
use App\Models\SampleBatch;
use App\Models\UserSampleInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SampleBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function user_sample_report()
    {
        $salesReps = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();

        $data['pagetitle'] = "Sample Report";
        $data['reps'] =  $salesReps;
        return view('sample-batch.sample_users',['data'=>$data]);
    }

    public function request_sample()
    {
        $products = SampleInventory::with('product')->get();
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;

        $samples = SampleRequest::with('product')->where("user_id",$user_id)->latest()->get();

        $data['pagetitle'] = 'Request Samples';
        $data['products'] = $products;
        $data['samples'] = $samples;
        return view('sample-batch.request_sample', ['data' =>$data]);
    }

    public function approve_sample_request()
    {
        //$samples = SampleRequest::with('product','user','sampleInventory','userSampleInventory')->where("approved_by",null)->latest()->get();
        $samples = SampleRequest::with(['product', 'user', 'sampleInventory'])
            ->leftJoin('user_sample_inventory', function ($join) {
                $join->on('sample_requests.user_id', '=', 'user_sample_inventory.user_id')
                    ->on('sample_requests.product_id', '=', 'user_sample_inventory.product_id');
            })
            ->select('sample_requests.*', 'user_sample_inventory.quantity as product_quantity')
            ->whereNull('approved_by')
            ->latest()
            ->get();
        $data['pagetitle'] = 'Approve Samples Request';
        $data['samples'] = $samples;
        return view('sample-batch.approve_request', ['data' =>$data]);
    }

    public function issue_sample_request()
    {

        $samples = SampleRequest::with(['product', 'user', 'sampleInventory'])
            ->leftJoin('user_sample_inventory', function ($join) {
                $join->on('sample_requests.user_id', '=', 'user_sample_inventory.user_id')
                    ->on('sample_requests.product_id', '=', 'user_sample_inventory.product_id');
            })
            ->select('sample_requests.*', 'user_sample_inventory.quantity as product_quantity')
            ->where("approved_by",!null)
            ->whereNull('issued_by')
            ->latest()
            ->get();
        $data['pagetitle'] = 'Issue Samples Request';
        $data['samples'] = $samples;
        return view('sample-batch.Issue_sample_request', ['data' =>$data]);
    }

    public function issue_user_sample($id,$userId)
    {
        $sample = SampleRequest::with('product')->where("id",$id)->first();
        $data['pagetitle'] = 'Approve Samples Request';
        $data['sample'] = $sample;
        $data['userId'] = $userId;
        return view('sample-batch.issue_user_sample_request', ['data' =>$data]);
    }

    public function issue_samples(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();
                return redirect('/');
            }
            $user_id = $user->id;

            foreach ($request->product_qty as $index => $qty) {
                // Check if both user_id and product_id arrays are set and not null
                if (isset($request->user_id[$index]) && isset($request->produc_id[$index])) {
                    $userId = $request->user_id[$index];
                    $productId = $request->produc_id[$index];

                    // Check if requested quantity is greater than available quantity
                    $sample_inventory = SampleInventory::where('product_id', $productId)->first();
                    if ($sample_inventory && $qty > $sample_inventory->quantity) {
                        continue; // Skip to the next product
                    }

                    // Proceed with creating or updating UserSampleInventory entry
                    $userInventory = UserSampleInventory::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

                    if ($userInventory) {
                        // User already has an entry for the product, so update the quantity
                        $userInventory->quantity += $qty;
                        $userInventory->save();
                    } else {
                        // User does not have an entry for the product, so create a new entry
                        UserSampleInventory::create([
                            'user_id' => $userId,
                            'product_id' => $productId,
                            'quantity' => $qty,
                        ]);
                    }

                    // Reducing the quantity from sample inventory
                    if ($sample_inventory) {
                        $quantity = $sample_inventory->quantity;
                        $total_quantity = $quantity - $qty;
                        $sample_inventory->quantity = $total_quantity;
                        $sample_inventory->save();
                    }

                    // update The sample request to indicate It has been issued
                    $sample_request = SampleRequest::find($request->sample_id[$index]);
                    if ($sample_request) {
                        $sample_request->quantity_issued += $qty;
                        $sample_request->issued_by = $user_id;
                        $sample_request->save();
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle any exceptions here
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

        // Return a success response if everything went well
        return redirect()->back()->with('success', 'Samples issued successfully.');
    }

    public function view_sample_request($id)
    {
        $sample = SampleRequest::with('product')->where("id",$id)->first();
        $data['pagetitle'] = 'Approve Samples Request';
        $data['sample'] = $sample;
        return view('sample-batch.approve_sample_request', ['data' =>$data]);
    }

    public function view_user_sample_request($id)
    {
        $sample = SampleRequest::with('product')->where("id",$id)->first();
        $data['pagetitle'] = 'Samples Request';
        $data['sample'] = $sample;
        return view('sample-batch.user_sample_request', ['data' =>$data]);
    }

    public function users()
    {
        // get User Id
        $data['products'] = SampleBatch::with(['product', 'user'])
            ->where('approved_by', '=', null)
            ->get();

        // Extract unique users from the products data
        $uniqueUsers = User::whereIn('id', $data['products']->pluck('user_id')->unique())->get();
        return view('sample-batch.user', ['users' =>$uniqueUsers]);
    }

    public function user_approve(Request $request)
    {
        $user_id = $request->user;
        $data['pagetitle'] = 'Approve Samples';
        $data['products'] = SampleBatch::with(['product', 'user'])
            ->where('approved_by', '=', null)
            ->get();
        return view('sample-batch.approve', ['data' => $data]);

    }

    public function sample_balance()
    {
        $salesReps = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();


        $data['pagetitle'] = "Sample Balance";
        $data['reps'] =  $salesReps;
        return view('sample-batch.sample_balance', ['data' => $data]);
    }

    public function user_sample_inventory()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $salescall = SalesCall::with('productSample')->where('created_by',$user_id)->get();


        $samples = UserSampleInventory::with('product')->where('user_id',$user_id)->get();

        $inventories = [];

        foreach ($samples as $sample){
            $product_id = $sample->product->id; // Corrected this line to get the product id instead of sample id
            $product_name = $sample->product->name;
            $current_inventory = $sample->quantity;
            $sample_issued = SampleRequest::where('user_id', $user_id)->where('product_id', $product_id)->sum('quantity_issued');

            // Retrieve sales calls made by the user
            $salescalls = SalesCall::with('productSample')->where('created_by', $user_id)->get();

            // Sum the quantity for the current product from the sales calls
            $sales_quantity = 0;
            foreach ($salescalls as $salescall) {
                foreach ($salescall->productSample as $productSample) {
                    if ($productSample->product_id == $product_id) {
                        $sales_quantity += $productSample->quantity;
                    }
                }
            }

            // Total quantity for the product considering both samples issued and sales calls
            $total_quantity = $sample_issued + $sales_quantity;
            // You might want to store this data for further processing
            $inventory_data = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'current_inventory' => $current_inventory,
                'sample_issued' => $sample_issued,
                'total_quantity' => $total_quantity,
            ];

            $inventories[] = $inventory_data; // Add inventory data to the array
        }


        $data['pagetitle'] = "Sample Inventory";
        $data['samples'] = $inventories;
        return view('sample-batch.user_sample_report', ['data' => $data]);
    }
    public function sample_inventory()
    {

        $samples = SampleInventory::with('product')->get();

        $data['pagetitle'] = "Sample Inventory";
        $data['samples'] = $samples;
        return view('sample-batch.samples_inventory', ['data' => $data]);
    }

    public function view_user_inventory($id)
    {
        $user_id = $id;
        $salescall = SalesCall::with('productSample')->where('created_by',$user_id)->get();


        $samples = UserSampleInventory::with('product')->where('user_id',$user_id)->get();

        $inventories = [];

        foreach ($samples as $sample){
            $product_id = $sample->product->id; // Corrected this line to get the product id instead of sample id
            $product_name = $sample->product->name;
            $current_inventory = $sample->quantity;
            $sample_issued = SampleRequest::where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->sum('quantity_issued');

            // Retrieve sales calls made by the user
            $salescalls = SalesCall::with('productSample')
                ->where('created_by', $user_id)
                ->where('created_at', '>=', '2024-04-08 09:57:19')
                ->get();

            // Sum the quantity for the current product from the sales calls
            $sales_quantity = 0;
            foreach ($salescalls as $salescall) {
                foreach ($salescall->productSample as $productSample) {
                    if ($productSample->product_id == $product_id) {
                        $sales_quantity += $productSample->quantity;
                    }
                }
            }

            // Total quantity for the product considering both samples issued and sales calls
            //$total_quantity = $sample_issued + $sales_quantity;
            $inventory_data = [
                'product_id' => $product_id,
                'product_name' => $product_name,
                'current_inventory' => $current_inventory,
                'sample_issued' => $sample_issued,
                'total_quantity' =>  $sales_quantity,
            ];

            $inventories[] = $inventory_data; // Add inventory data to the array
        }


        $data['pagetitle'] = "User Sample Inventory";
        $data['samples'] = $inventories;
        return view('sample-batch.view_user_inventory', ['data' => $data]);
    }

    public function edit_user_inventory($id)
    {
        $user_sample_inventory = UserSampleInventory::with('product')
                                                ->where('user_id',$id)
                                                ->get();
        //return  $user_sample_inventory;
        $data['pagetitle'] = "Edit user Sample Inventory";
        $data['samples'] =  $user_sample_inventory;
        return view('sample-batch.edit_user_inventory', ['data' => $data]);
    }


    public function update_user_inventory($id)
    {
        $user_sample_inventory = UserSampleInventory::with('product')
            ->where('id',$id)
            ->first();
        //return  $user_sample_inventory;
        $data['pagetitle'] = "Edit user Sample Inventory";
        $data['sample'] =  $user_sample_inventory;
        return view('sample-batch.update_user_inventory', ['data' => $data]);
    }

    public function updateUseInventory(Request $request,$id)
    {

        try{
            $validatedData = $request->validate([
                'product_id' => 'required|integer',
                'product' => 'required|string',
                'quantity' => 'required|integer|min:0',
            ]);

            $inventory = UserSampleInventory::findOrFail($id);

            // Update the inventory attributes based on the validated data
            $inventory->update([
                'quantity' => $validatedData['quantity'],
            ]);

            return redirect()->back()->with('success', 'Sample Inventory has been updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Products: ' . $e->getMessage());
        }
    }

    public function create_inventory()
    {
        $products = Product::all();
        $samples = SampleInventory::with('product')->get();

        $data['pagetitle'] = "Create Inventory";
        $data['products'] = $products;
        $data['samples'] = $samples;
        return view('sample-batch.create_samples', ['data' => $data]);
    }


    public function store_inventory(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:0',
            ]);

            $existingInventoryCount = SampleInventory::where('product_id', $validatedData['product_id'])->count();

            if ($existingInventoryCount > 0) {
                // If the product already exists, return an error response
                return redirect()->back()->with('error', 'Product already exists in the inventory');
            }else{
                $inventory = new SampleInventory();
                $inventory->product_id = $validatedData['product_id'];
                $inventory->quantity = $validatedData['quantity'];
                $inventory->save();
                return redirect()->route('sample-batch.create-inventory')->with('success', 'Product Sample created successfully');
            }


        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Tier Products: ' . $e->getMessage());
        }

    }


    public function update_inventory($id)
    {
      $sample = SampleInventory::with('product')->where('id',$id)->first();
      $data['sample'] = $sample;
      $data['pagetitle'] = "Update Inventory";
      return view('sample-batch.update_inventory', ['data' => $data]);
    }


    public function stock_update_inventory(Request $request, $id)
    {

        try{
            $validatedData = $request->validate([
                'product_id' => 'required',
                'quantity' => 'required|integer',
                'stock' => 'required|integer',
            ]);

            // Sum up quantity and stock
            $totalQuantity = $validatedData['quantity'] + $validatedData['stock'];

            $sample = SampleInventory::findOrFail($id);
            $sample->quantity = $totalQuantity;
            $sample->save();

            return redirect()->route('sample-batch.sample-inventory')->with('success', 'Product Sample updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Sample Products: ' . $e->getMessage());
        }
    }


    public function destroy_inventory($id)
    {

        try {
            $inventoryItem = SampleInventory::findOrFail($id);
            $inventoryItem->delete();
            return redirect()->route('sample-batch.create-inventory')->with('success', 'Product Sample Inventory item deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Tier Products: ' . $e->getMessage());
        }
    }



    // Check if the combination of team and tier already exists


    public function user_sample_balance($id)
    {
        $samples = UserSampleInventory::with('product')
            ->where("user_id", $id)
            ->get();
        $data['pagetitle'] = 'Request Samples';
        $data['samples'] = $samples;
        return view('sample-batch.rep_balance', ['data' => $data]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }
        $data['pagetitle'] = 'Request Samples';
        $data['products'] = Product::orderBy('name', 'asc')->get();

        return view('sample-batch.create', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function approve()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
        session()->flush();  // Clears all session data
        return redirect('/');
        }
        $data['pagetitle'] = 'Approve Samples';
        $data['products'] = SampleBatch::with(['product', 'user'])
            ->where('approved_by', '=', null)
            ->get();
        return view('sample-batch.approve', ['data' => $data]);
    }

    public function invoice()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $data['pagetitle'] = 'Approve Samples';
        $data['products'] = SampleBatch::with(['product', 'user'])
            ->where('Invoiced_by', '=', null)
            ->where('approved_by', '!=', null)
            ->get();
        return view('sample-batch.invoice', ['data' => $data]);
    }

    public function issued()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $data['pagetitle'] = 'Approve Samples';
        $data['products'] = SampleBatch::with(['product', 'user'])
            ->where('Issued_by', '=', null)
            ->where('invoiced_by', '!=', null)
            ->get();
        return view('sample-batch.issued', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store_sample(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:titles,id',
                'notes' => 'required|string|max:255',
                'product_qty' => 'required|string|max:255',
            ]);


            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;


            // Create new sample
            $sample_batch = new SampleBatch();
            $sample_batch->user_id = $user_id;
            $sample_batch->product_id = $validatedData['product_id'];
            $sample_batch->quantity_requested = $validatedData['product_qty'];
            $sample_batch->notes = $validatedData['notes'];
            $sample_batch->quantity_approved = 0;
            $sample_batch->quantity_dispatched = 0;
            $sample_batch->quantity_remaining = 0;
            $sample_batch->save();


            return redirect()->route('sample-batch.request_sample')->with('success', 'Sample Requested successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Sample Request Failed: ' . $e->getMessage());
        }
    }
    public function store_new_sample(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'notes' => 'nullable|string|max:255',
                'product_qty' => 'required|string|max:255',
            ]);


            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Create new sample
            $sample_request = new SampleRequest();
            $sample_request->user_id = $user_id;
            $sample_request->product_id = $validatedData['product_id'];
            $sample_request->quantity_requested = $validatedData['product_qty'];
            $sample_request->notes = $validatedData['notes'];
            $sample_request->save();


            return redirect()->route('sample-batch.request-sample')->with('success', 'Sample Requested successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Sample Request Failed: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        //dd($request->product_id);

        foreach ($request->product_id as $key => $product_id) {
            if ($product_id > 0) {
                $sample_batch = new SampleBatch();
                $sample_batch->user_id = $user_id;
                $sample_batch->product_id = $product_id;
                $sample_batch->quantity_requested = $request->product_qty[$key];
                $sample_batch->quantity_approved = 0;
                $sample_batch->quantity_dispatched = 0;
                $sample_batch->quantity_remaining = 0;
                $sample_batch->save();
            }
        }

        toastr()->success('Product Samples Requested successfully');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleBatch $sampleBatch)
    {
        //
    }

    public function report()
    {
        $filterDate =  date('Y-m-d');
        $endDate = date('Y-m-d');

        $samples = SampleRequest::query()
            ->whereBetween('created_at', [$filterDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('issued_by')
            ->get();

        //return $samples;


        $data['samples'] = $samples;
        $data['pagetitle'] = "Sample Reports";
        $data['filterDate'] = $filterDate;
        $data['endDate'] = $endDate;


        return view('sample-batch.admin_report',['data'=>$data]);
    }
    public function adminReport(Request $request)
    {
        $filterDate = $request->input('filter_date');
        $endDate = $request->input('end_date');

        if (!$filterDate) {
            $filterDate = date('Y-m-d');
        }
        if (!$endDate) {
            $endDate = date('Y-m-d');
        }


        $samples = SampleRequest::query();

        // Filter by date range if both filter_date and end_date are provided
        if ($filterDate && $endDate) {
            $samples->whereDate('created_at', '>=', $filterDate)
                ->whereDate('created_at', '<=', $endDate)
                ->whereNotNull('issued_by');
        }
        // Fetch the samples data
        $samples = $samples->get();

        $data['samples'] = $samples;
        $data['pagetitle'] = "Sample Reports";
        $data['filterDate'] = $filterDate;
        $data['endDate'] = $endDate;
        return view('sample-batch.admin_report',['data'=>$data]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleBatch $sampleBatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        //dd($request->product_id);

        foreach ($request->sample_batch_id as $key => $sample_batch_id) {
                $sample_batch = SampleBatch::find($sample_batch_id);
                //$sample_batch->product_id = $product_id;
                $sample_batch->quantity_approved = $request->product_qty[$key];
                $sample_batch->quantity_dispatched = $request->product_qty[$key];
                $sample_batch->quantity_remaining = 0;
                $sample_batch->approved_by = $user_id;
                $sample_batch->update();
        }

        toastr()->success('Product Samples Requested Approved');
        return redirect()->route('sample-batch.approve');
    }

    public function approve_samples(Request $request)
    {
        try {

            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;


            foreach ($request->sample_id as $key => $sample_id) {
                $sample_batch = SampleRequest::find($sample_id);
                $sample_batch->quantity_approved = $request->product_qty[$key];
                $sample_batch->approved_by = $user_id;
                $sample_batch->update();
            }

            return redirect()->route('sample-batch.approve-sample-request')->with('success', 'Sample Requested Approved successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Sample Request Approval Failed: ' . $e->getMessage());
        }
    }

    public function edit_sample_request()
    {
        $sample_requests = SampleRequest::with('product','user')->latest()->get();
        $data['samples'] = $sample_requests;
        $data['pagetitle'] = "Edit Reports";
        return view('sample-batch.sample_request_edit',['data'=>$data]);
    }

    public function editSampleRequest($id,$userId)
    {
        $sample_request = SampleRequest::findOrFail($id);
        $data['sample'] = $sample_request;
        $data['userId'] = $userId;
        $data['pagetitle'] = "Edit Reports";
        return view('sample-batch.edit_sample_request',['data'=>$data]);
    }


    public function updateSampleRequest(Request $request, $id)
    {
        $sample_request = SampleRequest::find($id);

// Update the quantity fields
        $sample_request->quantity_approved = $request->qty_app;
        $sample_request->quantity_issued = $request->qty_issued;

// Update the approved_by and issued_by fields
        $sample_request->approved_by = $request->app_by ? $request->app_by : null;
        $sample_request->issued_by = $request->issued_by ? $request->issued_by : null;

// Save the changes
        $sample_request->update();

        return redirect()->route('sample-batch.edit-sample-request')->with('success', 'Sample Requested Updated successfully');
    }

    public function destroySampleRequest($id)
    {
        // Find the SampleRequest by ID
        $sample_request = SampleRequest::find($id);
        $sample_request->delete();
        return redirect()->route('sample-batch.edit-sample-request')->with('success', 'Sample Requested deleted successfully');
    }

    public function approve_user_sample(Request $request, $id)
    {
        try {

            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            $sample_batch = SampleRequest::find($id);
            $sample_batch->quantity_approved = $request->approved_qty;
            $sample_batch->approved_by = $user_id;
            $sample_batch->comments = $request->comment;
            $sample_batch->update();


            return redirect()->route('sample-batch.approve-sample-request')->with('success', 'Sample Requested Approved successfully');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Sample Request Approval Failed: ' . $e->getMessage());
        }
    }

    public function issue_sample_user(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            if (isset($request->userId) && isset($request->product_id)) {
                $userId = $request->userId;
                $productId = $request->product_id;
                $qty = $request->issued_qty;

                // Check if requested quantity is greater than available quantity
                $sample_inventory = SampleInventory::where('product_id', $productId)->first();

                if ($sample_inventory && $qty > $sample_inventory->quantity) {
                    return redirect()->back()->with('error', 'Sample Request Approval Failed: INVENTORY LOW');
                }


                // Proceed with creating or updating UserSampleInventory entry
                $userInventory = UserSampleInventory::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();

                if ($userInventory) {
                    // User already has an entry for the product, so update the quantity
                    $userInventory->quantity += $qty;
                    $userInventory->save();
                } else {
                    // User does not have an entry for the product, so create a new entry
                    UserSampleInventory::create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $qty,
                    ]);
                }

                // Reducing the quantity from sample inventory
                if ($sample_inventory) {
                    $quantity = $sample_inventory->quantity;
                    $total_quantity = $quantity - $qty;
                    $sample_inventory->quantity = $total_quantity;
                    $sample_inventory->save();
                }

                // update The sample request to indicate It has been issued
                $sample_request = SampleRequest::find($id);
                if ($sample_request) {
                    $sample_request->quantity_issued += $qty;
                    $sample_request->issued_by = $user_id;
                    $sample_request->save();
                }
            }

            return redirect()->route('sample-batch.issue-sample-request')->with('success', 'Sample Requested Issued successfully');

        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Sample Request Approval Failed: ' . $e->getMessage());
        }

    }

    public function invoiced(Request $request)
    {

        $user = Auth::user();
        $user_id = $user->id;
        //dd($request->product_id);

        foreach ($request->sample_batch_id as $key => $sample_batch_id) {
            $sample_batch = SampleBatch::find($sample_batch_id);
            $sample_batch->quantity_invoiced = $request->product_qty[$key];
            $sample_batch->Invoiced_by = $user_id;
            $sample_batch->update();
        }

        toastr()->success('Product Samples Invoiced');
        return redirect()->route('sample-batch.invoice');
    }

    public function issuance(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        //dd($request->product_id);

        foreach ($request->sample_batch_id as $key => $sample_batch_id) {
            $sample_batch = SampleBatch::find($sample_batch_id);
            $sample_batch->quantity_issued = $request->product_qty[$key];
            $sample_batch->Issued_by = $user_id;
            $sample_batch->quantity_remaining = $request->product_qty[$key];
            $sample_batch->update();
        }

        toastr()->success('Product Samples Issued');
        return redirect()->route('sample-batch.issued');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleBatch $sampleBatch)
    {
        //
    }
}
