<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchDetailStoreRequest;
use App\Http\Requests\ProductionBatchDetailUpdateRequest;
use App\Models\DraftOrder;
use App\Models\InputBatch;
use App\Models\Phase;
use App\Models\Product;
use App\Models\ProductionOrderPhaseDetail;
use App\Models\ProductionSetting;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderPhase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

class ProductionOrderController extends Controller
{

    public function index(Request $request): View
    {
        $data['pagetitle'] = 'Production Orders';
        $data['production_orders'] = ProductionOrder::with(['productionorderphases','product'])->latest()->paginate();
        $data['draft_production_orders'] = config('settings.draft_production_orders');


        return view('productionorders.index', ['data' => $data]);
    }

    public function create(Request $request)
    {

        $user = Auth::user();
        $role = $user->roles;

        $data['role'] = $role[0]->name;
        $products = Product::get(['id','name','code','price']);

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $data['ingredient_batches'] = InputBatch::with('input')->where('quantity_remaining', '>=', 1)->get();

        $data['packaging_batches'] = InputBatch::with('input')->get();
        $data['miscellaneous_batches'] = InputBatch::with('input')->get();
        $data['products'] = $products;


        $data['pagetitle'] = 'Start New Production Batch';

        $data['product_settings'] = ProductionSetting::with('product')->paginate();
        $data['draft_production_orders'] = config('settings.draft_production_orders');

        //return  $data['product_settings'];

        return view('productionorders.create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        //Check if the "Save Draft" button is clicked
        if ($request->has('save_draft')) {

            $draft_order = new DraftOrder();
            $draft_order->product_id = $request->product_id;
            $draft_order->no_of_phases = $request->no_of_phases;
            $draft_order->rows_per_phase = $request->rows_per_phase;
            $draft_order->category = $request->category;
            $draft_order->product_quantity_target = $request->product_quantity_target;
            $draft_order->packaging_phase = $request->packaging_phase;
            $draft_order->labour_phase = $request->labour_phase;
            $draft_order->total_batch_quantity = $request->total_batch_quantity;
            $draft_order->total_batch_cost = $request->total_batch_cost;
            $draft_order->save();
        } else {

            $production_order = new ProductionOrder();

            $qty_target = str_replace(',', '', $request->production_quantity_target);

            $production_order->production_quantity_target = $qty_target;
            $production_order->total_batch_weight = 0;
            $production_order->total_batch_cost = $request->total_batch_cost;
            $production_order->total_batch_quantity = $request->total_batch_quantities;
            $production_order->created_by = Auth::id();
            $production_order->reviewed_by = Auth::id();
            $production_order->approved_by = Auth::id();
            $production_order->save();

            $input_ids_array = $request->input_id;
            $percentages_array = $request->percentage;
            $packsizes_array = $request->packsize;
            $batchqty_array = $request->batchqty;
            $grams_array = $request->grams_to_be_added;
            //$costs_array = $request->cost;
            $packaging_array = $request->packaging_id;

            //dd($packaging_array, $request->phaseids);

            $get_array_key_access = 0;

            foreach ($request->phaseids as $phaseid) {
                $production_order_phase = new ProductionOrderPhase();
                $production_order_phase->production_setting_id = $request->get('production_setting_id');
                $production_order_phase->production_order_id = $production_order->id;
                $production_order_phase->phase_id = $phaseid;
                $production_order_phase->save();
                $production_order_phase_id = $production_order_phase->id;
                //for ($i = 1; $i <= $request->rows_per_phase; $i++) {
                //    print "<li><u>$key</u> :: $get_array_key_access";
                $get_array_key_access++;
                //}

                /*
                 * For phase1 its array 0
                 * Pick item 0 & 1
                 *
                 * For phase2
                 * Pick item 2 & 3
                 */

                foreach ($percentages_array as $key => $percentage) {
                    $input_id = $input_ids_array[$key];
                    if ($input_id != "select_option") {
                        $pieces = explode("_", $input_id);
                        $input_id = $pieces[0];
                        $getphase = $pieces[1];

                        if ($getphase == $phaseid) {

                            $qty_consumed = $grams_array[$key];

                            $production_order_phase_detail = new ProductionOrderPhaseDetail();
                            $production_order_phase_detail->production_order_phase_id = $production_order_phase_id;
                            $production_order_phase_detail->production_order_id = $production_order->id;
                            $production_order_phase_detail->input_id = $input_id;
                            $production_order_phase_detail->percentage = $percentage;
                            $production_order_phase_detail->weight = $qty_consumed;
                            $production_order_phase_detail->pack_size_id = 1;
                            $production_order_phase_detail->save();
                            self::reduceItemQuantity($input_id, $qty_consumed);
                        }
                    }
                }
            }


            //dd($packsizes_array, $batchqty_array, $packaging_array);

            foreach ($packaging_array as $key2 => $packaging) {
                $input_id2 = $packaging_array[$key2];
                if ($input_id2 != "select_option") {
                    $pieces2 = explode("_", $input_id2);
                    $input_id2 = $pieces2[0];
                    $qty_consumed2 = $batchqty_array[$key2];
                    //$getphase2 = $pieces2[1];
                    //if ($getphase2 == $phaseid) {
                    $production_order_phase_detail = new ProductionOrderPhaseDetail();
                    $production_order_phase_detail->production_order_phase_id = $production_order_phase_id;
                    $production_order_phase_detail->production_order_id = $production_order->id;
                    $production_order_phase_detail->input_id = $input_id2;
                    $production_order_phase_detail->percentage = $packsizes_array[$key2];
                    $production_order_phase_detail->weight = $qty_consumed2;
                    $production_order_phase_detail->pack_size_id = 1;
                    $production_order_phase_detail->save();

                    self::reduceItemQuantity($input_id2, $qty_consumed2);
                    //}
                }
            }

            //$request->session()->flash('productionBatchDetail.id', $productionBatchDetail->id);

        }
        return redirect()->route('production-order.index');
    }

    public function show(Request $request, ProductionOrder $production_order)
    {
        $production_order_id = $production_order->id;


        // $production_order = ProductionOrder::with(['productionsetting', 'productionorderphases', 'productionorderdetails'])
        //     ->find($production_order_id);
        $production_order = ProductionOrder::with(['productionorderphases', 'product'])
            ->find($production_order_id);


        $data['pagetitle'] = "View Production Order";
        $data['production_order'] = $production_order;
        return view('productionorders.show', ['data' => $data]);
    }

    public function edit(Request $request, ProductionOrder $productionBatchDetail): Response
    {
        return view('productionBatchDetail.edit', compact('productionBatchDetail'));
    }

    public function reduceItemQuantity($input_id, $quantity_consumed)
    {
        $input_batch = InputBatch::where('quantity_remaining', '>', 0)
            ->where('quantity_remaining', '>', $quantity_consumed)
            ->where('input_id', '=', $input_id)
            ->first();

        $remaining_quantity = $input_batch->quantity_remaining;
        $balance = $remaining_quantity - $quantity_consumed;

        $input_batch->quantity_remaining = $balance;
        $input_batch->update();
    }

    public function update(Request $request, ProductionOrder $productionorder)
    {
        $actual_weights = $request->actual_weights;


        $order = ProductionOrder::with('productionorderdetails')->find($request->production_order);

        $order->status = 'finalized';
        $order->ph = $request->ph;
        $order->viscocity = $request->viscocity;
        $order->color = $request->color;
        $order->smell = $request->smell;
        $order->expiry_date = $request->expiry_date;
        $order->accidental_losses = $request->accidental_losses;

        $i = 0;
        foreach ($order->productionorderdetails as $productionorderdetail) {
            $productionorderdetail->actual_weight = $actual_weights[$i];
            $productionorderdetail->update();
            $i++;
        }

        $order->update();

        return redirect()->route('production-order.index');
    }

    public function destroy(Request $request, ProductionOrder $productionBatchDetail): Response
    {
        $productionBatchDetail->delete();

        return redirect()->route('production-batch-detail.index');
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles;

        $data['role'] = $role[0]->name;

        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $data['ingredient_batches'] = InputBatch::with('input')->get();


        $data['packaging_batches'] = InputBatch::with('input')->get();
        $data['miscellaneous_batches'] = InputBatch::with('input')->get();
        $data['phases'] = Phase::all();

        $data['pagetitle'] = 'Start New Production Batch';
        $data['product_setting_id'] = $request->product_setting_id;
        $data['category'] = $request->category;
        $data['product_quantity_target'] = $request->product_quantity_target;
        $total_no_of_phases = $request->no_of_phases;
        if ($request->incPackagingPhase == "Yes") {
            $total_no_of_phases++;
        }
        if ($request->incLaborPhase == "Yes") {
            $total_no_of_phases++;
        }
        $data['no_of_phases'] = $request->no_of_phases;
        $data['total_no_of_phases'] = $total_no_of_phases;
        $data['rows_per_phase'] = $request->rows_per_phase;
        $data['incPackagingPhase'] = $request->incPackagingPhase;
        $data['incLaborPhase'] = $request->incLaborPhase;

        $data['product_settings'] = ProductionSetting::with('product')->get();
        $data['draft_production_orders'] = $request->draft_production_orders;

        return view('productionorders.create-step2', ['data' => $data]);
    }

    public function print(Request $request, ProductionOrder $production_order)
    {
        $production_order_id = $production_order->id;

        $production_order = ProductionOrder::with(['productionsetting', 'productionorderphases', 'productionorderdetails'])
            ->find($production_order_id);

        $data['pagetitle'] = "Print Production Order";
        $data['production_order'] = $production_order;
        return view('productionorders.print', ['data' => $data]);
    }
    public function storeProductionOrder(Request $request){

        // $receivedData =  $request->all() ;
        $receivedData = $request->phasesData;
        $packagingData = $request->packagingData;
        $labourData = $request->laborData;


        //save a production orders table first before saving the phases

        $productionOrder = ProductionOrder::create([
            'product_id' => $request->prodId,
            'production_quantity_target' => $request->prodQnt,
            'production_quantity_actual' =>200,
            'total_batch_weight' => 300,
            'production_quantity_actual' =>250
        ]);


        //grouping the packaging data per row
        $packagingArray = [];

        // Loop through each row of packaging data
        for ($i = 0; $i < count($packagingData); $i += 5) {
            // Assuming each row repeats every 5 elements
            $rowData = [];
            // Extract a row of data (5 elements) and add them to the row data array
            for ($j = $i; $j < $i + 5; $j++) {
                // Strip the [] from the key using str_replace
                $key = str_replace(['[', ']'], '', $packagingData[$j]['name']);
                $rowData[$key] = $packagingData[$j]['value'];
            }
            // Add the row data to the packaging array
            $packagingArray[] = $rowData;
        }
        $packagingArray['packaging'] = $packagingArray;

        //grouping the labor data per row
        $laborArray = [];

        // Loop through each row of packaging data
        for ($i = 0; $i < count($labourData); $i += 5) {
            // Assuming each row repeats every 5 elements
            $rowData = [];
            // Extract a row of data (5 elements) and add them to the row data array
            for ($j = $i; $j < $i + 5; $j++) {
                // Strip the [] from the key using str_replace
                $key = str_replace(['[', ']'], '', $labourData[$j]['name']);
                $rowData[$key] = $labourData[$j]['value'];
            }
            // Add the row data to the packaging array
            $laborArray[] = $rowData;
        }
        $laborArray['labor'] = $laborArray;
        //update the production orders table with the labor and the packagiing phases
        $productionOrder->update([
            'packaging' => $packagingArray['packaging'],
            'labor' => $laborArray['labor']
        ]);



        $transformedData = [];

        foreach ($receivedData as $phase => $phaseData) {
            if (is_array($phaseData)) {
                $phaseArray = [];

                // Loop through each row of phase data
                for ($i = 0; $i < count($phaseData); $i += 5) { // Assuming each row repeats every 5 elements
                    $rowData = [];
                    // Extract a row of data (5 elements) and add them to the row data array
                    for ($j = $i; $j < $i + 5; $j++) {
                        // Strip the [] from the key using str_replace
                        $key = str_replace(['[', ']'], '', $phaseData[$j]['name']);
                        $rowData[$key] = $phaseData[$j]['value'];
                    }
                    // Add the row data to the phase array
                    $phaseArray[] = $rowData;
                }

                // Add the phase array to the transformed data
                $transformedData[$phase] = $phaseArray;
            }
        }

        //saving the data to database
        foreach ($transformedData as $phase => $phaseData) {
            $jsonPhaseDetails = json_encode($phaseData);
            $productionDetail = new ProductionOrderPhase();
            $productionDetail->phase = $phase;
            $productionDetail->json_phase_details = $jsonPhaseDetails;
            $productionDetail->production_order_id = $productionOrder->id;
            $productionDetail->save();

        }
        return true ;
    }
}
