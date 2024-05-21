<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Facility;
use App\Http\Controllers\LengthAwarePaginator;
class TargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $teamId = $user->team_id;
            $userId = $user->id;
            $currentYear = Carbon::now()->year;
            $products = Product::where('team_id', $teamId)
                ->with(['targets' => function ($query) use ($currentYear, $userId) {
                    $query->whereYear('created_at', $currentYear)
                        ->where('user_id', $userId);
                }])
                ->paginate(10);

        }
        return view('targets.index', ['products' => $products, 'currentYear' => $currentYear]);
    }

    public function customers()
    {
        $user = Auth::user();

        $clients = $user->facilities->unique();

        return view('targets.customer', ['data' => $clients]);
    }


    public function sales_rep_target()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $userId = $user->id;


        $user = Auth::user();
        $userId = $user->id;


        $currentMonth = \Carbon\Carbon::now()->month;
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
        $pharmacies = $user->pharmacies()->get();

        // Initialize arrays to store grouped items for facilities and pharmacies
        $facilityGroupedItems = [];
        $pharmacyGroupedItems = [];

        // Loop through facilities
        foreach ($facilities as $facility) {
            // Get facility code and product IDs for this facility
            $facilityCode = $facility->code;
            $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this facility
            foreach ($facilityProductIds as $facilityProductId) {
                // Fetch the product information
                $product = Product::find($facilityProductId);
                $product_code = $product->code;
                $month = strtolower(Carbon::now()->format('F'));

                // Filter target IDs based on facility code
                $targetIds = Targets::where('code', $facilityCode)
                    ->where('product_id', $facilityProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter',  $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;

                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $facilityCode,
                ];
            }
        }


        //return $facilityGroupedItems;

        // Loop through pharmacies
        foreach ($pharmacies as $pharmacy) {
            // Get pharmacy code and product IDs for this pharmacy
            $pharmacyCode = $pharmacy->code;
            $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this pharmacy
            foreach ($pharmacyProductIds as $pharmacyProductId) {
                // Fetch the product information
                $product = Product::find($pharmacyProductId);
                $product_code = $product->code;
                $month = strtolower(Carbon::now()->format('F'));


                // Filter target IDs based on pharmacy code
                $targetIds = Targets::where('code', $pharmacyCode)
                    ->where('product_id', $pharmacyProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter',  $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;

                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $pharmacyCode,
                ];
            }
        }

        //return $pharmacyGroupedItems;
        $mergedItems = array_merge($facilityGroupedItems, $pharmacyGroupedItems);

        // Initialize an array to store the combined grouped items
        $combinedGroupedItems = [];

        // Loop through each merged item
        foreach ($mergedItems as $code => $items) {
            // Loop through items under each code
            foreach ($items as $productId => $item) {
                // If the product ID exists in the combined array, add the metrics to it
                if (isset($combinedGroupedItems[$productId])) {
                    $combinedGroupedItems[$productId]['total_quantity'] += $item['total_quantity'] ?? 0;
                    $combinedGroupedItems[$productId]['total_target'] += $item['total_target'] ?? 0;
                    $combinedGroupedItems[$productId]['product_code'] = $item['product_code'] ?? null;
                    $combinedGroupedItems[$productId]['product'] = $item['product'] ?? null;
                    $combinedGroupedItems[$productId]['target_value'] += $item['target_value'] ?? 0;


                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,
                    ];
                }
            }
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

        // Get all years from the sales data
        $years = Sale::distinct()->pluck(DB::raw('YEAR(date)'))->toArray();

        //return $combinedGroupedItems;
        return view('targets.salesrep_target', [
            'groupedItems' => $combinedGroupedItems, // Use the modified $parent array with variance data
            'user_id' => $userId,
            'months' => $months,
            'years' => $years,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }

    public function user_monthly_target($userId,$productCode, $month, $year)
    {
        $currentMonth = $month;
        $currentYear = $year;
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

        $carbonInstance = \Carbon\Carbon::create(null, $currentMonth, 1, 0, 0, 0);
        $month = strtolower($carbonInstance->format('F'));

        $user = User::find($userId);

        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();

        $product = Product::where('code',$productCode)->first();
        $product_id = $product->id;
        $product_code = $product->code;

        // Get all the facilities and pharmacy that have this product id
        // Retrieve all facilities and pharmacies related to the user
        // Loop through each facility

        foreach ($facilities as $facility) {
            // Check if the product ID exists in the facility's product IDs array
            if (in_array($product_id, json_decode($facility->pivot->product_ids, true) ?? [])) {
                // If the product ID exists, add the facility to the result array
                $facilitiesWithProduct[] = $facility;
            }
        }
        //return $facilities2;

        foreach ($pharmacies as $pharmacy) {
            // Check if the product ID exists in the facility's product IDs array
            if (in_array($product_id, json_decode($pharmacy->pivot->product_ids, true) ?? [])) {
                // If the product ID exists, add the facility to the result array
                $pharmaciesWithProduct[] = $pharmacy;
            }
        }

        // Initialize arrays to store the report data
        $facilityReport = [];
        $pharmacyReport = [];

        if (!empty($facilitiesWithProduct)) {
            // Your processing logic for pharmacies
            // Process facilities
            foreach ($facilitiesWithProduct as $facility) {
                $facilityCode = $facility->code;
                // Get target for the current facility
                $targetId = Targets::where('product_id', $product_id)
                    ->where('user_id', $userId)
                    ->where('code',$facilityCode)
                    ->where('quarter',  $quarterNumber)
                    ->value('id');
                // return $targetId;
                //$month = strtolower(Carbon::now()->format('F'));
                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $facility->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');

                // Add facility data to the report array
                $facilityReport[] = [
                    'facility' => $facility,
                    'target' => $target,
                ];
            }
        }

        if (!empty($pharmaciesWithProduct)) {
            // Your processing logic for pharmacies
            // Process pharmacies
            foreach ($pharmaciesWithProduct as $pharmacy) {
                $facilityCode = $pharmacy->code;
                // Get target for the current pharmacy
                $targetId = Targets::where('product_id', $product_id)
                    ->where('user_id', $userId)
                    ->where('code',$facilityCode)
                    ->where('quarter',  $quarterNumber)
                    ->value('id');
                // return $targetId;
                //$month = strtolower(Carbon::now()->format('F'));
                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $pharmacy->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');

                // Add pharmacy data to the report array
                $pharmacyReport[] = [
                    'pharmacy' => $pharmacy,
                    'target' => $target,
                ];
            }
        }

        $report = [
            'facilities' => $facilityReport,
            'pharmacies' => $pharmacyReport,
        ];

        // Combine facility and pharmacy reports into a single array
        $combinedReport = array_merge($facilityReport, $pharmacyReport);

        usort($combinedReport, function($a, $b) {
            // Get customer code for facility from array $a
            $customerCodeA = isset($a['facility']) ? $a['facility']->customer_code : null;

            // Get customer code for pharmacy from array $b
            $customerCodeB = isset($b['pharmacy']) ? $b['pharmacy']->customer_code : null;

            // Compare customer codes
            return strcmp($customerCodeA, $customerCodeB);
        });

        return view('targets.userMonthlyTargets', ['report' => $combinedReport]);
    }

    public function monthly_user_target_filter(Request $request)
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $userId = $user->id;


        $currentMonth = $request->month;
        $currentYear = $request->year;

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

        $user = User::find($userId);

        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();


        // Initialize arrays to store grouped items for facilities and pharmacies
        $facilityGroupedItems = [];
        $pharmacyGroupedItems = [];

        // Loop through facilities
        foreach ($facilities as $facility) {
            // Get facility code and product IDs for this facility
            $facilityCode = $facility->code;
            $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this facility
            foreach ($facilityProductIds as $facilityProductId) {
                // Fetch the product information
                $product = Product::find($facilityProductId);
                $product_code = $product->code;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);


                // Filter target IDs based on facility code
                $targetIds = Targets::where('code', $facilityCode)
                    ->where('product_id', $facilityProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));


                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');



                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;


                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $facilityCode,
                ];
            }
        }


        //return $facilityGroupedItems;

        // Loop through pharmacies
        foreach ($pharmacies as $pharmacy) {
            // Get pharmacy code and product IDs for this pharmacy
            $pharmacyCode = $pharmacy->code;
            $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this pharmacy
            foreach ($pharmacyProductIds as $pharmacyProductId) {
                // Fetch the product information
                $product = Product::find($pharmacyProductId);
                $product_code = $product->code;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);


                // Filter target IDs based on pharmacy code
                $targetIds = Targets::where('code', $pharmacyCode)
                    ->where('product_id', $pharmacyProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;

                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $pharmacyCode,
                ];
            }
        }

        //return $pharmacyGroupedItems;
        $mergedItems = array_merge($facilityGroupedItems, $pharmacyGroupedItems);

        // Initialize an array to store the combined grouped items
        $combinedGroupedItems = [];

        // Loop through each merged item
        foreach ($mergedItems as $code => $items) {
            // Loop through items under each code
            foreach ($items as $productId => $item) {
                // If the product ID exists in the combined array, add the metrics to it
                if (isset($combinedGroupedItems[$productId])) {
                    $combinedGroupedItems[$productId]['total_quantity'] += $item['total_quantity'] ?? 0;
                    $combinedGroupedItems[$productId]['total_target'] += $item['total_target'] ?? 0;
                    $combinedGroupedItems[$productId]['product_code'] = $item['product_code'] ?? null;
                    $combinedGroupedItems[$productId]['product'] = $item['product'] ?? null;
                    $combinedGroupedItems[$productId]['target_value'] += $item['target_value'] ?? 0;

                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,
                    ];
                }
            }
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

        // Get all years from the sales data
        $years = Sale::distinct()->pluck(DB::raw('YEAR(date)'))->toArray();

        //return $combinedGroupedItems;
        return view('targets.salesrep_target', [
            'groupedItems' => $combinedGroupedItems, // Use the modified $parent array with variance data
            'user_id' => $userId,
            'months' => $months,
            'years' => $years,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }

    public function accumulated_targets()
    {
        $salesReps = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();
        $data['pagetitle'] = "Planner for Sales Reps";
        $data['reps'] =  $salesReps;
        return view('targets.view_targets',['data'=>$data]);
    }


    public function user_target($id)
    {

        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $userId = $id;
        $user = User::find($userId);

        $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));

        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];
        $month = strtolower($monthName);

        $quarterNumber = null;
        foreach ($quarters as $quarter => $data) {
            if (in_array($monthName , $data['months'])) {
                $quarterNumber = $quarter;
                break;
            }
        }

        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();

        // Initialize arrays to store grouped items for facilities and pharmacies
        $facilityGroupedItems = [];
        $pharmacyGroupedItems = [];

        // Loop through facilities
        foreach ($facilities as $facility) {
            // Get facility code and product IDs for this facility
            $facilityCode = $facility->code;
            $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this facility
            foreach ($facilityProductIds as $facilityProductId) {
                // Fetch the product information
                $product = Product::find($facilityProductId);
                $product_code = $product->code;


                // Filter target IDs based on facility code
                $targetIds = Targets::where('code', $facilityCode)
                    ->where('product_id', $facilityProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;

                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $facilityCode,
                ];
            }
        }


        //return $facilityGroupedItems;

        // Loop through pharmacies
        foreach ($pharmacies as $pharmacy) {
            // Get pharmacy code and product IDs for this pharmacy
            $pharmacyCode = $pharmacy->code;
            $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this pharmacy
            foreach ($pharmacyProductIds as $pharmacyProductId) {
                // Fetch the product information
                $product = Product::find($pharmacyProductId);
                $product_code = $product->code;


                // Filter target IDs based on pharmacy code
                $targetIds = Targets::where('code', $pharmacyCode)
                    ->where('product_id', $pharmacyProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;


                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $pharmacyCode,
                ];
            }
        }

        //return $pharmacyGroupedItems;
        $mergedItems = array_merge($facilityGroupedItems, $pharmacyGroupedItems);

        // Initialize an array to store the combined grouped items
        $combinedGroupedItems = [];

        // Loop through each merged item
        foreach ($mergedItems as $code => $items) {
            // Loop through items under each code
            foreach ($items as $productId => $item) {
                // If the product ID exists in the combined array, add the metrics to it
                if (isset($combinedGroupedItems[$productId])) {
                    $combinedGroupedItems[$productId]['total_quantity'] += $item['total_quantity'] ?? 0;
                    $combinedGroupedItems[$productId]['total_target'] += $item['total_target'] ?? 0;
                    $combinedGroupedItems[$productId]['product_code'] = $item['product_code'] ?? null;
                    $combinedGroupedItems[$productId]['product'] = $item['product'] ?? null;
                    $combinedGroupedItems[$productId]['target_value'] += $item['target_value'] ?? 0;


                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,

                    ];
                }
            }
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

        // Get all years from the sales data
        $years = Sale::distinct()->pluck(DB::raw('YEAR(date)'))->toArray();

        //return $combinedGroupedItems;

        //return $facilityGroupedItems;
        return view('targets.userTargets', [
            'groupedItems' => $combinedGroupedItems,
            'user_id' => $userId,
            'currentMonth' => $currentMonth,
            'currentYear' =>   $currentYear,
            'months' => $months,
            'years' => $years
        ]);
    }

    public function monthlyTargets($userId,$productCode,$month,$year)
    {

        $currentMonth = $month;
        $currentYear = $year;
        $user = User::find($userId);
        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();

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

        $product = Product::where('code',$productCode)->first();
        $prodcuct_id = $product->id;
        $product_code = $product->code;

        // Get all the facilities and pharmacy that have this product id
        // Retrieve all facilities and pharmacies related to the user
        // Loop through each facility

        foreach ($facilities as $facility) {
            // Check if the product ID exists in the facility's product IDs array
            if (in_array($prodcuct_id, json_decode($facility->pivot->product_ids, true) ?? [])) {
                // If the product ID exists, add the facility to the result array
                $facilitiesWithProduct[] = $facility;
            }
        }
        //return $facilities2;

        foreach ($pharmacies as $pharmacy) {
            // Check if the product ID exists in the facility's product IDs array
            if (in_array($prodcuct_id, json_decode($pharmacy->pivot->product_ids, true) ?? [])) {
                // If the product ID exists, add the facility to the result array
                $pharmaciesWithProduct[] = $pharmacy;
            }
        }

        // Initialize arrays to store the report data
        $facilityReport = [];
        $pharmacyReport = [];

        if (!empty($facilitiesWithProduct)) {
            // Your processing logic for pharmacies
            // Process facilities
            foreach ($facilitiesWithProduct as $facility) {
                $facilityCode = $facility->code;
                // Get target for the current facility
                $targetId = Targets::where('product_id',$prodcuct_id)
                    ->where('user_id', $userId)
                    ->where('code', $facilityCode)
                    ->where('quarter', $quarterNumber)
                    ->pluck(DB::raw('MIN(id)'))
                    ->first();
                // return $targetId;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);

                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $facility->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');
                // Get sales quantity for the current facility
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $product_code)
                    ->where('customer_code',$facilityCode)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                // Add facility data to the report array
                $facilityReport[] = [
                    'facility' => $facility,
                    'target' => $target,
                    'sales_quantity' => $totalQuantity,
                ];
            }
        }

        //return  $facilityReport;

        if (!empty($pharmaciesWithProduct)) {
            // Your processing logic for pharmacies
            // Process pharmacies
            foreach ($pharmaciesWithProduct as $pharmacy) {
                $facilityCode = $pharmacy->code;
                // Get target for the current pharmacy
                $targetId = Targets::where('product_id',$prodcuct_id)
                    ->where('user_id', $userId)
                    ->where('code', $facilityCode)
                    ->where('quarter', $quarterNumber)
                    ->pluck(DB::raw('MIN(id)'))
                    ->first();
                // return $targetId;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);
                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $pharmacy->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');
                // Get sales quantity for the current facility
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $product_code)
                    ->where('customer_code',$facilityCode)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                // Add pharmacy data to the report array
                $pharmacyReport[] = [
                    'pharmacy' => $pharmacy,
                    'target' => $target,
                    'sales_quantity' => $totalQuantity,
                ];
            }
        }

        $report = [
            'facilities' => $facilityReport,
            'pharmacies' => $pharmacyReport,
        ];

        // Combine facility and pharmacy reports into a single array
        $combinedReport = array_merge($facilityReport, $pharmacyReport);

        usort($combinedReport, function($a, $b) {
            // Get customer code for facility from array $a
            $customerCodeA = isset($a['facility']) ? $a['facility']->customer_code : null;

            // Get customer code for pharmacy from array $b
            $customerCodeB = isset($b['pharmacy']) ? $b['pharmacy']->customer_code : null;

            // Compare customer codes
            return strcmp($customerCodeA, $customerCodeB);
        });
        // Pass the combined report to the view
        return view('targets.monthlyTargets', ['report' => $combinedReport]);
    }

    public function monthlyTargetsFilters(Request $request, $userId)
    {

        $currentMonth = $request->month;
        $currentYear = $request->year;

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
        $user = User::find($userId);

        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();

        // Initialize arrays to store grouped items for facilities and pharmacies
        $facilityGroupedItems = [];
        $pharmacyGroupedItems = [];

        // Loop through facilities
        foreach ($facilities as $facility) {
            // Get facility code and product IDs for this facility
            $facilityCode = $facility->code;
            $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this facility
            foreach ($facilityProductIds as $facilityProductId) {
                // Fetch the product information
                $product = Product::find($facilityProductId);
                $product_code = $product->code;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);


                // Filter target IDs based on facility code
                $targetIds = Targets::where('code', $facilityCode)
                    ->where('product_id', $facilityProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;
                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;


                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $facilityCode,
                ];
            }
        }


        //return $facilityGroupedItems;

        // Loop through pharmacies
        foreach ($pharmacies as $pharmacy) {
            // Get pharmacy code and product IDs for this pharmacy
            $pharmacyCode = $pharmacy->code;
            $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

            // Loop through product IDs for this pharmacy
            foreach ($pharmacyProductIds as $pharmacyProductId) {
                // Fetch the product information
                $product = Product::find($pharmacyProductId);
                $product_code = $product->code;
                $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                $month = strtolower($monthName);


                // Filter target IDs based on pharmacy code
                $targetIds = Targets::where('code', $pharmacyCode)
                    ->where('product_id', $pharmacyProductId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->where('quarter', $quarterNumber)
                    ->groupBy('code')
                    ->pluck(DB::raw('MIN(id)'));

                // Get the total target for the current product ID
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where('month', $month)
                    ->groupBy(['target_id', 'month'])
                    ->get()
                    ->sum('target');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Calculate metrics
                $targetValue = $productPrice * $totalTarget;

                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'code' => $pharmacyCode,
                ];
            }
        }

        //return $pharmacyGroupedItems;
        $mergedItems = array_merge($facilityGroupedItems, $pharmacyGroupedItems);

        // Initialize an array to store the combined grouped items
        $combinedGroupedItems = [];

        // Loop through each merged item
        foreach ($mergedItems as $code => $items) {
            // Loop through items under each code
            foreach ($items as $productId => $item) {
                // If the product ID exists in the combined array, add the metrics to it
                if (isset($combinedGroupedItems[$productId])) {
                    $combinedGroupedItems[$productId]['total_quantity'] += $item['total_quantity'] ?? 0;
                    $combinedGroupedItems[$productId]['total_target'] += $item['total_target'] ?? 0;
                    $combinedGroupedItems[$productId]['product_code'] = $item['product_code'] ?? null;
                    $combinedGroupedItems[$productId]['product'] = $item['product'] ?? null;
                    $combinedGroupedItems[$productId]['target_value'] += $item['target_value'] ?? 0;

                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,

                    ];
                }
            }
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

        // Get all years from the sales data
        $years = Sale::distinct()->pluck(DB::raw('YEAR(date)'))->toArray();

        //return $combinedGroupedItems;

        //return $facilityGroupedItems;
        return view('targets.userTargets', [
            'groupedItems' => $combinedGroupedItems,
            'user_id' => $userId,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'months' => $months,
            'years' => $years
        ]);

    }

    public function pharmacy()
    {
        $user = Auth::user();

        $clients = $user->pharmacies->unique();

        return view('targets.pharmacies', ['data' => $clients]);
    }



    public function customersTarget(Request $request)
    {

            $validatedData = $request->validate([
                'customer' => 'required',
            ]);
            $user = Auth::user();
            $userId = $user->id;
            $facilityId = $request->customer;
            $cust_name = Facility::where('id',$facilityId)->value('name');


            $pivotRow = $user->facilities()->where('facility_id', $facilityId)->where('user_id', $userId)->first();
            $code = $pivotRow->code;
            $productIds = json_decode($pivotRow->pivot->product_ids, true);
            $currentYear = Carbon::now()->year;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }
        $quarter = $request->input('quarter', 1);

        $overallTargets = Targets::where('user_id', $userId)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;

        $productOverallTargets = $overallTargets->groupBy(['product_id', 'quarter']);;

        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });
        //return $combinedData;

            return view('targets.clincs',[
                'products' => $combinedData,
                'code' => $code,
                'currentYear' => $currentYear,
                'customer' => $facilityId,
                'name' => $cust_name,
            ]);

    }

    public function adminFacilities(Request $request)
    {
        $user_id = $request->user;
        $user = User::findOrFail($user_id);
        $pharmacies = $user->pharmacies()->get();
        $facilities = $user->facilities()->get();

        $data = [];

        foreach ($pharmacies as $pharmacy) {
            $data[] = [
                'type' => 'pharmacy',
                'id' => $pharmacy->id,
                'name' => $pharmacy->name,
                'code' => $pharmacy->code,
                'facility_type' => $pharmacy->facility_type,
            ];
        }

        foreach ($facilities as $facility) {
            $data[] = [
                'type' => 'facility',
                'id' => $facility->id,
                'name' => $facility->name,
                'code' => $facility->code,
                'facility_type' => $facility->facility_type,
            ];
        }

        return view('targets.admin_facilities',['data'=>$data, 'user_id'=>$user_id]);
    }

    public function adminPharmaciesTargets($id,$user_id)
    {
        $facilityId = $id;
        $user = User::findOrFail($user_id);
        $user_full_name = $user->first_name . ' ' . $user->last_name;
        $cust_name = Pharmacy::where('id',$facilityId)->value('name');

        $pivotRow = $user->pharmacies()->where('pharmacy_id', $facilityId)->where('user_id', $user_id)->first();
        $code = $pivotRow->code;
        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }


        $overallTargets = Targets::where('user_id', $user_id)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;

        $productOverallTargets = $overallTargets->groupBy(['product_id', 'quarter']);;

        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });
        return view('targets.admin_targets',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
            'name' => $cust_name,
            'user_name' => $user_full_name,
            'user_id' => $user_id,
            'type' => 'pharmacy'
        ]);
    }

    public function adminFacilityTargets($id,$user_id)
    {
        $facilityId = $id;
        $user = User::findOrFail($user_id);
        $user_full_name = $user->first_name . ' ' . $user->last_name;
        $cust_name = Facility::where('id',$facilityId)->value('name');

        $pivotRow = $user->facilities()->where('facility_id', $facilityId)->where('user_id', $user_id)->first();
        $code = $pivotRow->code;
        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }


        $overallTargets = Targets::where('user_id', $user_id)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;

        $productOverallTargets = $overallTargets->groupBy(['product_id', 'quarter']);;

        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });
        return view('targets.admin_targets',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
            'name' => $cust_name,
            'user_name' => $user_full_name,
            'user_id' => $user_id,
            'type' => 'clinic'
        ]);

    }

    public function pharmacyTarget(Request $request)
    {
            $validatedData = $request->validate([
                'customer' => 'required',
            ]);
            $user = Auth::user();
            $userId = $user->id;
            $facilityId = $request->customer;
            $cust_name = Pharmacy::where('id',$facilityId)->value('name');

            $pivotRow = $user->pharmacies()->where('pharmacy_id', $facilityId)->where('user_id', $userId)->first();
            $code = $pivotRow->code;
            $productIds = json_decode($pivotRow->pivot->product_ids, true);
            $currentYear = Carbon::now()->year;
            //return $facilityId;
            if (!is_null($productIds)) {
                $products = Product::whereIn('id', $productIds)->get();
            } else {
                $products = collect();
            }
            //return $products->target;

            $quarter = $request->input('quarter', 1);

            $overallTargets = Targets::where('user_id', $userId)
                ->where('year', $currentYear)
                ->where('code', $code)
                ->get();
            //return $overallTargets;

            $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
                ->orderBy('created_at', 'desc')
                ->get();
            //return $monthlyTargets;

            $productOverallTargets = $overallTargets->groupBy(['product_id', 'quarter']);;

            $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
                // Get all overall targets for the product
                $productOverallTargets = $overallTargets->where('product_id', $product->id);

                // Initialize an array to store all monthly targets
                $product->monthlyTargets = [];

                foreach ($productOverallTargets as $overallTarget) {
                    $targetId = $overallTarget->id;

                    // Filter monthly targets based on the target_id
                    $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                    // Merge the filtered monthly targets into the overall monthly targets array
                    $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
                }

                return $product;
            });

            //return $combinedData;
            return view('targets.phamacy_set',[
                'products' => $combinedData,
                'code' => $code,
                'currentYear' => $currentYear,
                'customer' => $facilityId,
                'name' => $cust_name,
            ]);
    }

    public function pharmacyQuarter(Request $request)
    {

        $validatedData = $request->validate([
            'customer' => 'required',
        ]);

        $user = Auth::user();
        $userId = $user->id;
        $facilityId = $request->customer;

        //return $facilityId;

        $pivotRow = $user->pharmacies()->where('pharmacy_id', $facilityId)->where('user_id', $userId)->first();
        $code = $pivotRow->code;
        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        $products = Product::whereIn('id', $productIds)->paginate(10);
        //return $products->target;

        $quarter = $request->input('quarter', 1);

        $overallTargets = Targets::where('user_id', $userId)
            ->where('quarter', $quarter)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->whereIn('month', ['january', 'february', 'march', 'april'])
            ->orderBy('created_at', 'desc')
            ->get();

        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get overall target for the product
            $product->overallTarget = $overallTargets->where('product_id', $product->id)->first();

            // Get monthly targets for the product based on the overall target
            if ($product->overallTarget) {
                $targetId = $product->overallTarget->id;
                $product->monthlyTargets = $monthlyTargets->where('target_id', $targetId)->all();
            } else {
                $product->monthlyTargets = [];
            }

            return $product;
        });

        //return $combinedData;

        return view('targets.phamacy_set',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
        ]);

    }

    public function adminIndex()
    {
        $currentYear = Carbon::now()->year;
        $usersWithUserRole = [];

        $users = User::all();

        foreach ($users as $user) {
            $userRoles = $user->roles->pluck('name')->toArray();

            // Check if the user has the "user" role
            if (in_array('user', $userRoles)) {
                // User has the "user" role, add to the result array
                $usersWithUserRole[] = $user;
            }
        }

        return view('targets.admin_index', ['users' => $usersWithUserRole, 'currentYear' => $currentYear]);
    }



    public function adminTargets(Request $request)
    {

        $userId = $request->user;
        $user = User::findOrFail($userId);

        if ($user) {
            $teamId = $user->team_id;
            $currentYear = Carbon::now()->year;

            $products = Product::where('team_id', $teamId)
                ->with(['targets' => function ($query) use ($currentYear, $userId) {
                    $query->whereYear('created_at', $currentYear)
                        ->where('user_id', $userId);
                }])
                ->get();
        }
        return view('targets.admin_set', ['products' => $products, 'currentYear' => $currentYear]);

    }

    public function set($id, $code)
    {
       $product = Product::where('id', $id)->get();
        $data['pagetitle'] = 'Set Target';
        $data['product'] =  $product;
        $data['code'] =  $code;
        return view('targets.set', ['data' => $data]);
    }

    public function set_clinic($id, $code)
    {
            $product = Product::where('id', $id)->get();
            $data['pagetitle'] = 'Set Target';
            $data['product'] =  $product;
            $data['code'] =  $code;
            return view('targets.set_clinic', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id, $code)
    {
        try {
            $request->validate([
                'quarter' => 'required|in:1,2,3,4',
            ]);

            $target = new Targets();
            $target->product_id = $id;
            $target->user_id = auth()->id();
            $target->code = $code;
            $target->quarter = $request->input('quarter');
            $target->target = $this->calculateTotalTarget($request, $target->quarter);
            $target->year = now()->year;

            $target->save();

            // Save monthly targets
            $targetId = $target->id;
            $this->saveMonthlyTargets($targetId, $request);


            return $this->showTargetPharmacyPage($code);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('targets.pharmacy')->with('error', 'An error occurred while adding the target' . $errorMessage);;
        }
    }
    public function showTargetPharmacyPage($code)
    {

        $user = Auth::user();
        $userId = $user->id;
        $Id = Pharmacy::where('code',$code)->value('id');
        $facilityId = $Id;
        $cust_name = Pharmacy::where('id',$Id)->value('name');


        $pivotRow = $user->pharmacies()->where('pharmacy_id', $Id)->where('user_id', $userId)->first();
        $code = $pivotRow->code;
        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        //return $facilityId;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }
        //return $products->target;


        $overallTargets = Targets::where('user_id', $userId)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;


        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });

        toastr()->success('Target Added successfully');
        //return $combinedData;
        return view('targets.phamacy_set',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
            'name' => $cust_name,

        ]);
    }

    public function store_clinic(Request $request, $id, $code)
    {
        try {
            $request->validate([
                'quarter' => 'required|in:1,2,3,4',
            ]);

            $target = new Targets();
            $target->product_id = $id;
            $target->user_id = auth()->id();
            $target->code = $code;
            $target->quarter = $request->input('quarter');
            $target->target = $this->calculateTotalTarget($request, $target->quarter);
            $target->year = now()->year;

            $target->save();


            // Save monthly targets
            $targetId = $target->id;
            $this->saveMonthlyTargets($targetId, $request);

            return $this->showTargetClinicPage($code)->with('success', 'Target Added Successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('targets.customers')->with('error', 'An error occurred while adding the target' . $errorMessage);;
        }
    }

    public function showTargetClinicPage($facilityId)
    {
        $user = Auth::user();
        $userId = $user->id;
        $id = Facility::where('code',$facilityId)->pluck('id');
        $cust_name = Facility::where('id',$id)->value('name');

        $pivotRow = $user->facilities()->where('facility_id', $id)->where('user_id', $userId)->first();
        $code = $pivotRow->code;
        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }

        $overallTargets = Targets::where('user_id', $userId)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;


        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });
        //return $combinedData;

        toastr()->success('Target Added successfully');

        return view('targets.clincs',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
            'name' => $cust_name,
        ]);
    }
// Helper function to calculate total target dynamically based on selected months
    private function calculateTotalTarget($request, $quarter)
    {
        $totalTarget = 0;

        // Determine the months based on the selected quarter
        $months = $this->getMonthsInQuarter($quarter);

        // Calculate the total target
        foreach ($months as $month) {
            $totalTarget += $request->input($month);
        }

        return $totalTarget;
    }

// Helper function to get the months in a given quarter
    private function getMonthsInQuarter($quarter)
    {
        // Define your quarter-month mapping
        $quarterMonths = [
            1 => ['january', 'february', 'march'],
            2 => ['april','may', 'june'],
            3 => ['july', 'august','september'],
            4 => [ 'october', 'november', 'december'],
        ];

        return $quarterMonths[$quarter] ?? [];
    }

// Helper function to save monthly targets
    private function saveMonthlyTargets($targetId, $request)
    {
        // Determine the months based on the selected quarter
        $quarter = $request->input('quarter');
        $months = $this->getMonthsInQuarter($quarter);

        // Save monthly targets
        foreach ($months as $month) {
            $this->saveMonthlyTarget($targetId, $month, $request->input($month));
        }
    }

// Helper function to save individual monthly target
    private function saveMonthlyTarget($targetId, $month, $target)
    {
        $targetMonth = new TargetMonths();
        $targetMonth->target_id = $targetId;
        $targetMonth->month = $month;
        $targetMonth->target = $target;
        $targetMonth->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, $code)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $pharmacyName = Pharmacy::where('code', $code)->value('name');
        $product = Product::where('id', $id)->value('name');
        $type = "Pharmacy";

        $currentYear = Carbon::now()->year;

        $targets = Targets::selectRaw('MIN(id) as id, quarter')
            ->where('product_id', $id)
            ->where('code', $code)
            ->where('user_id', $user_id)
            ->where('year', $currentYear)
            ->groupBy('quarter')
            ->get();

        //return $targets;

        $targetsByQuarter = [];

        foreach ($targets as $target) {
            // Fetch target months for the current quarter
            $monthlyTargets = TargetMonths::where('target_id', $target->id)->get();

            // Initialize array to hold target months for the current quarter
            $quarterTargets = [];

            // Loop through each target month
            foreach ($monthlyTargets as $month) {
                // Populate array with month, target, and ID values
                $quarterTargets[] = [
                    'id' => $month->id,
                    'month' => $month->month,
                    'target' => $month->target,
                ];
            }

            // Store target months for the current quarter in the main array
            $targetsByQuarter[$target->quarter] = $quarterTargets;
        }

        //return $targetsByQuarter;
        $data['targetsByQuarter'] = $targetsByQuarter;
        $data['pagetitle'] = "Edit targets";
        $data['code'] = $code;
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['product'] =  $product;
        return view('targets.edit', ['data' => $data]);
    }

    public function edit_clinic($id, $code)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $facilityName = Facility::where('code', $code)->value('name');
        $product = Product::where('id', $id)->value('name');
        $type = "Pharmacy";

        $currentYear = Carbon::now()->year;

        $targets = Targets::selectRaw('MIN(id) as id, quarter')
            ->where('product_id', $id)
            ->where('code', $code)
            ->where('user_id', $user_id)
            ->where('year', $currentYear)
            ->groupBy('quarter')
            ->get();

        //return $targets;

        $targetsByQuarter = [];

        foreach ($targets as $target) {
            // Fetch target months for the current quarter
            $monthlyTargets = TargetMonths::where('target_id', $target->id)->get();

            // Initialize array to hold target months for the current quarter
            $quarterTargets = [];

            // Loop through each target month
            foreach ($monthlyTargets as $month) {
                // Populate array with month, target, and ID values
                $quarterTargets[] = [
                    'id' => $month->id,
                    'month' => $month->month,
                    'target' => $month->target,
                ];
            }

            // Store target months for the current quarter in the main array
            $targetsByQuarter[$target->quarter] = $quarterTargets;
        }

        //return $targetsByQuarter;
        $data['targetsByQuarter'] = $targetsByQuarter;
        $data['pagetitle'] = "Edit targets";
        $data['code'] = $code;
        $data['user_id'] = $user_id;
        $data['type'] = $type;
        $data['product'] =  $product;
        return view('targets.edit_clinic', ['data' => $data]);
    }

    public function edit_admin($id,$code,$user,$type)
    {
        $product_id = $id;
        $customer_code = $code;
        $user_id = $user;
        $sales = User::find($user_id);
        $user_name = $sales->first_name . ' ' . $sales->last_name;
        $product = Product::where('id',$product_id)->value('name');

        $customer = null;

        $pharmacyName = Pharmacy::where('code', $code)->value('name');
        $facilityName = Facility::where('code', $code)->value('name');

        if ($pharmacyName !== null) {
            $customer = $pharmacyName;
        } elseif ($facilityName !== null) {
            $customer = $facilityName;
        }
        $currentYear = Carbon::now()->year;

        $targets = Targets::selectRaw('MIN(id) as id, quarter')
            ->where('product_id', $product_id)
            ->where('code', $customer_code)
            ->where('user_id', $user_id)
            ->where('year', $currentYear)
            ->groupBy('quarter')
            ->get();
        //return $targets;
        $targetsByQuarter = [];

        foreach ($targets as $target) {
            // Fetch target months for the current quarter
            $monthlyTargets = TargetMonths::where('target_id', $target->id)->get();

            // Initialize array to hold target months for the current quarter
            $quarterTargets = [];

            // Loop through each target month
            foreach ($monthlyTargets as $month) {
                // Populate array with month, target, and ID values
                $quarterTargets[] = [
                    'id' => $month->id,
                    'month' => $month->month,
                    'target' => $month->target,
                ];
            }

            // Store target months for the current quarter in the main array
            $targetsByQuarter[$target->quarter] = $quarterTargets;
        }

        //return  $targetsByQuarter;

        $data['targetsByQuarter'] = $targetsByQuarter;
        $data['pagetitle'] = "Edit targets";
        $data['product'] = $product;
        $data['user'] = $user_name;
        $data['customer'] = $customer;
        $data['code'] = $customer_code;
        $data['user_id'] = $user_id;
        $data['type'] = $type;


        return view('targets.admin_edit_target',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Targets $targets)
    {
        //update_clinic_targets
    }

    public function update_clinic_targets(Request $request,$code)
    {

        try {
            $user = Auth::user();
            $targetIds = $request->input('target_ids');
            $updatedTargets = $request->except('_token', 'target_ids');

            //return $updatedTargets;

            foreach ($targetIds as $index => $targetId) {
                $target = TargetMonths::find($targetId);
                if ($target) {
                    if ($target->month) {
                        $month = $target->month;
                        if (array_key_exists($month, $updatedTargets)) {
                            $target->update([
                                'target' => $updatedTargets[$month]
                            ]);
                        }
                    }
                }
            }

            return $this->showTargetClinicPage($code)->with('success', 'Targets updated successfully');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('targets.customers')->with('error', 'An error occurred while adding the target' . $errorMessage);;
        }

    }
    public function update_pharmacy_targets(Request $request,$code)
    {
        try {
            $user = Auth::user();
            $targetIds = $request->input('target_ids');
            $updatedTargets = $request->except('_token', 'target_ids');

            //return $updatedTargets;

            foreach ($targetIds as $index => $targetId) {
                $target = TargetMonths::find($targetId);
                if ($target) {
                    if ($target->month) {
                        $month = $target->month;
                        if (array_key_exists($month, $updatedTargets)) {
                            $target->update([
                                'target' => $updatedTargets[$month]
                            ]);
                        }
                    }
                }
            }

            return $this->showTargetPharmacyPage($code);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->route('targets.customers')->with('error', 'An error occurred while adding the target' . $errorMessage);;
        }
    }

    public function update_targets(Request $request,$code,$user,$type)
    {
        $targetIds = $request->input('target_ids');
        $updatedTargets = $request->except('_token', 'target_ids');

        //return $updatedTargets;

        foreach ($targetIds as $index => $targetId) {
            $target = TargetMonths::find($targetId);
            if ($target) {
                if ($target->month) {
                    $month = $target->month;
                    if (array_key_exists($month, $updatedTargets)) {
                        $target->update([
                            'target' => $updatedTargets[$month]
                        ]);
                    }
                }
            }
        }
        $user_id = $user;
        return $this->showTargetFacilityPage($code,$user_id,$type);

    }

    public function showTargetFacilityPage($code,$user_id,$type)
    {

        $user = User::findOrFail($user_id);
        if( $type == 'pharmacy'){
            $facilityId = Pharmacy::where('code',$code)->value('id');
            $cust_name = Pharmacy::where('id',$facilityId)->value('name');
            $pivotRow = $user->pharmacies()->where('pharmacy_id', $facilityId)->where('user_id', $user_id)->first();
            $code = $pivotRow->code;
            $type = 'pharmacy';
        }elseif($type == 'clinic'){
            $facilityId = Facility::where('code',$code)->pluck('id');
            $cust_name = Facility::where('id',$facilityId)->value('name');
            $pivotRow = $user->facilities()->where('facility_id', $facilityId)->where('user_id', $user_id)->first();
            $code = $pivotRow->code;
            $type = 'clinic';
        }

        $user = User::findOrFail($user_id);
        $user_full_name = $user->first_name . ' ' . $user->last_name;



        $productIds = json_decode($pivotRow->pivot->product_ids, true);
        $currentYear = Carbon::now()->year;
        if (!is_null($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
        } else {
            $products = collect();
        }


        $overallTargets = Targets::where('user_id', $user_id)
            ->where('year', $currentYear)
            ->where('code', $code)
            ->get();
        //return $overallTargets;

        $monthlyTargets = TargetMonths::whereIn('target_id', $overallTargets->pluck('id')->toArray())
            ->orderBy('created_at', 'desc')
            ->get();
        //return $monthlyTargets;

        $productOverallTargets = $overallTargets->groupBy(['product_id', 'quarter']);;

        $combinedData = $products->map(function ($product) use ($overallTargets, $monthlyTargets) {
            // Get all overall targets for the product
            $productOverallTargets = $overallTargets->where('product_id', $product->id);

            // Initialize an array to store all monthly targets
            $product->monthlyTargets = [];

            foreach ($productOverallTargets as $overallTarget) {
                $targetId = $overallTarget->id;

                // Filter monthly targets based on the target_id
                $filteredMonthlyTargets = $monthlyTargets->where('target_id', $targetId);

                // Merge the filtered monthly targets into the overall monthly targets array
                $product->monthlyTargets = array_merge($product->monthlyTargets, $filteredMonthlyTargets->toArray());
            }

            return $product;
        });

        toastr()->success('Target updated successfully');
        return view('targets.admin_targets',[
            'products' => $combinedData,
            'code' => $code,
            'currentYear' => $currentYear,
            'customer' => $facilityId,
            'name' => $cust_name,
            'user_name' => $user_full_name,
            'user_id' => $user_id,
            'type' => $type
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Targets $targets)
    {
        //
    }
}
