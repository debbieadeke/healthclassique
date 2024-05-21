<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Models\Sale;
use App\Http\Controllers\Controller;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Tests\Integration\Database\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;


class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sale.index');
    }


    public function quarterReport(Request $request)
    {
        // Get the current year
        $currentYear = date('Y');

        $filter_start_date = $currentYear . '-01-01';
        $filter_end_date = $currentYear . '-12-31';


        $userTeams = User::pluck('team_id', 'id');
        $uniqueUserIds = Sale::whereBetween('date', [
            $filter_start_date . ' 00:00:00',
            $filter_end_date . ' 23:59:59'
        ])
            ->distinct()
            ->pluck('user_id');

        $salesReps = collect();

        foreach ($uniqueUserIds as $userId) {
            $teamId = $userTeams->get($userId);
            $teamName = Team::find($teamId)->name ?? 'Admin';

            // Include date filter in the Sale query
            $employeeName = Sale::where('user_id', $userId)
                ->whereBetween('date', [
                    $filter_start_date . ' 00:00:00',
                    $filter_end_date . ' 23:59:59'
                ])->value('employee_name');

            $salesReps->put($userId, ['team_name' => $teamName, 'employee_name' => $employeeName]);
        }

        $data['salesReps'] = $salesReps;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;
        $data['filter_month'] =  $filter_start_date;

        return view('sale.quarterreport', ['data' => $data]);

    }

    public function userMonthlyReport2()
    {
        $user = Auth::user();
        $userId = $user->id;


        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get unique product codes from sales table for the given user and date range
        $productCodes = Sale::where('user_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->distinct('product_code')
            ->pluck('product_code');

        //return $productCodes;

// Get the targets for the product codes
        $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
            ->whereIn('products.code', $productCodes)
            ->get()
            ->groupBy('products.code');
        //return $targets;
        $totalTarget = 0;
        $groupedItems = [];

        foreach ($productCodes as $productCode) {
            // Get product details
            $product = Product::where('code', $productCode)->first();

            // Check if product exists
            if ($product) {
                $productId = $product->id;
                $targetIds = Targets::where('product_id', $productId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->groupBy(DB::raw('QUARTER(created_at)'))
                    ->pluck(DB::raw('MIN(id)'));
                //return $targetIds ;

                // If there are target IDs, calculate the sum of targets for the current product
                if (!empty($targetIds)) {
                    $month = strtolower(Carbon::now()->format('F'));
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->sum('target');

                }
                //return $totalTarget;

                // Initialize total quantity for the current product code
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $productCode)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Target value
                $targetValue = $productPrice * $totalTarget;

                // Achieved Value
                $achievedValue = $productPrice * $totalQuantity;

                // Percentage performance
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Add grouped item to the array
                $groupedItems[$productCode] = [
                    'product_code' => $productCode,
                    'product' => $product->name,
                    'total_quantity' => $totalQuantity,
                    'total_target' => $totalTarget,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
                ];
            }
            //return $groupedItems;
        }
        return view('sale.userMonthlyReport', [
            'groupedItems' => $groupedItems, // Use the modified $parent array with variance data
            'targets' => $targets,
            'user_id' => $userId,
        ]);
    }

    public function userMonthlyReportOld()
    {

        ini_set('max_execution_time', 600);


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

                // Get the total quantity of sales for the current product ID
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('customer_code', $facilityCode)
                    ->where('product_code', $product_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

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
                $achievedValue = $productPrice * $totalQuantity;
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
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

                // Get the total quantity of sales for the current product ID
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('customer_code', $pharmacyCode)
                    ->where('product_code', $product_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                //return  $totalQuantity;

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
                $achievedValue = $productPrice * $totalQuantity;
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
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
                    $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                    // Calculate performance percentage
                    $targetValue = $combinedGroupedItems[$productId]['target_value'];
                    $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                    $combinedGroupedItems[$productId]['percentage_performance'] =
                        $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,
                        'achieved_value' => $item['achieved_value'] ?? 0,

                        // Calculate performance percentage
                        'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
        return view('sale.userMonthlyReport', [
            'groupedItems' => $combinedGroupedItems, // Use the modified $parent array with variance data
            'user_id' => $userId,
            'months' => $months,
            'years' => $years,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }

    public function userMonthlyReport()
    {
        $user = Auth::user();
        $userId = $user->id;

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $cacheKey = "userMonthlyReport_{$userId}_{$currentMonth}_{$currentYear}";

        $reportData = Cache::remember($cacheKey, 24 * 60, function () use ($user, $userId, $currentMonth, $currentYear) {
            $user = User::find($userId);

            $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));

            $quarters = [
                1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
                2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
                3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
                4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]];
            $quarterNumber = null;
            foreach ($quarters as $quarter => $data) {
                if (in_array($monthName, $data['months'])) {
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                        'code' => $pharmacyCode,
                    ];
                }
            }

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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
            $years = Sale::distinct()->pluck(DB::raw('YEAR(date)'))->toArray();

            return [
                'groupedItems' => $combinedGroupedItems,
                'user_id' => $userId,
                'months' => $months,
                'years' => $years,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear,
            ];
        });

        // Get the cached report data
        //$reportData = Cache::get($cacheKey);

        // Return the view with the cached data
        return view('sale.userMonthlyReport', $reportData);
    }


    public function monthlyUserReportFilter(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;


        $currentMonth = $request->month;
        $currentYear = $request->year;

        $cacheKey = "monthlyUserReportFilter_{$userId}_{$currentMonth}_{$currentYear}";

        $reportData = Cache::remember($cacheKey, 24 * 60, function () use ($user, $userId, $currentMonth, $currentYear) {
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
            return [
                'groupedItems' => $combinedGroupedItems,
                'user_id' => $userId,
                'months' => $months,
                'years' => $years,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear,
            ];
        });
        return view('sale.userMonthlyReport', $reportData);
    }



    public function monthlyUserReportFilter2(Request $request)
    {
        $user = Auth::user();
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

                // Get the total quantity of sales for the current product ID
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('customer_code', $facilityCode)
                    ->where('product_code', $product_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

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
                $achievedValue = $productPrice * $totalQuantity;
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Store the calculated metrics with facility code as the key in the grouped items array
                $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
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

                // Get the total quantity of sales for the current product ID
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('customer_code', $pharmacyCode)
                    ->where('product_code', $product_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                //return  $totalQuantity;

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
                $achievedValue = $productPrice * $totalQuantity;
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Store the calculated metrics with pharmacy code as the key in the grouped items array
                $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
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
                    $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                    // Calculate performance percentage
                    $targetValue = $combinedGroupedItems[$productId]['target_value'];
                    $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                    $combinedGroupedItems[$productId]['percentage_performance'] =
                        $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                } else {
                    // If the product ID doesn't exist, create a new entry for it
                    $combinedGroupedItems[$productId] = [
                        'total_quantity' => $item['total_quantity'] ?? 0,
                        'total_target' => $item['total_target'] ?? 0,
                        'product_code' => $item['product_code'] ?? null,
                        'product' => $item['product'] ?? null,
                        'target_value' => $item['target_value'] ?? 0,
                        'achieved_value' => $item['achieved_value'] ?? 0,

                        // Calculate performance percentage
                        'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
        return view('sale.userMonthlyReport', [
            'groupedItems' => $combinedGroupedItems, // Use the modified $parent array with variance data
            'user_id' => $userId,
            'months' => $months,
            'years' => $years,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }

    public function userMonthlyFacilities($userId,$productCode, $month, $year)
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

        $carbonInstance = Carbon::create(null, $currentMonth, 1, 0, 0, 0);
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

        return view('sale.userMonthlyFacilities', ['report' => $combinedReport]);

    }
    public function userMonthlyFacilities2($userId,$productCode)
    {
        $user = Auth::user();



        $filter_start_date = now()->toDateString();
        $filter_end_date = now()->toDateString();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $salesFacilities = Sale::where('user_id', $userId)
            ->where('product_code', $productCode)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->groupBy('customer_code')
            ->select('customer_code','customer_name', \DB::raw('SUM(quantity) as total_quantity'))
            ->get();
        //return $userId;
        foreach ($salesFacilities as $facility) {
            $facilityCode = $facility->customer_code;
            //return $facilityCode;
            $productId = Product::where('code', $productCode)
                ->value('id');
            //return $productId;
            $targetId = Targets::where('product_id', $productId)
                ->where('user_id', $userId)
                ->where('code',$facilityCode)
                ->value('id');
            // return $targetId;
            $month = strtolower(Carbon::now()->format('F'));
            $target = TargetMonths::where('target_id',$targetId)
                ->where('month', $month)
                ->value('target');
            //return $target;
            $facility->target = TargetMonths::where('target_id', $targetId)
                ->where('month', $month)
                ->value('target');
        }
        // return $salesFacilities ;


        return view('sale.reportfacilities', [
            'salesFacilities' => $salesFacilities,
            'user_id' => $userId,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date,
        ]);

    }
    public function quarterlyRepItems($userId, Request $request)
    {
        $currentYear = Carbon::now()->year;
        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];

        $groupedItems = [];
        $productCodes = [];

        // Get unique product codes from sales table for the given user and date range
        $productCodes = Sale::where('user_id', $userId)
            ->whereYear('date', $currentYear)
            ->distinct('product_code')
            ->pluck('product_code');

        foreach ($quarters as $quarter => $dates) {
            $startMonth = $dates['start'];
            $endMonth = $dates['end'];
            $months = $dates['months'];

            foreach ($productCodes as $productCode) {
                // Calculate targets for the product code within the quarter
                $targetIds = Targets::join('products', 'targets.product_id', '=', 'products.id')
                    ->where('products.code', $productCode)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->pluck('targets.id');

                // Calculate total target for the product code within the quarter
                $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                    ->where(function ($query) use ($months) {
                        // Filter by the range of months
                        $query->whereIn('month', $months);
                    })
                    ->sum('target');

                // Calculate total quantity sold for the product code within the quarter
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $productCode)
                    ->where(function ($query) use ($startMonth, $endMonth, $currentYear) {
                        $query->whereMonth('date', '>=', Carbon::parse($startMonth)->month)
                            ->whereMonth('date', '<=', Carbon::parse($endMonth)->month)
                            ->whereYear('date', $currentYear);
                    })
                    ->sum('quantity');

                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Calculate target value, achieved value, and percentage performance
                $targetValue = $product->price * $totalTarget;
                $achievedValue = $product->price * $totalQuantity;
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Add grouped item to the array
                $groupedItems[$productCode . '_Q' . $quarter] = [
                    'product_code' => $productCode,
                    'product' => $product->name,
                    'quarter' => 'Q' . $quarter,
                    'total_quantity' => $totalQuantity,
                    'total_target' => $totalTarget,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
                ];
            }
        }


        //return $groupedItems;

        return view('sale.quarterRepItems', [
            'groupedItems' => $groupedItems, // Use the modified $groupedItems array
            'user_id' => $userId,
        ]);

    }

    public function quarterlyRepItems22($userId, Request $request)
    {
        $currentYear = Carbon::now()->year;

        $quarters = [
            1 => ['start' => 'January', 'end' => 'March', 'months' => ['January', 'February', 'March']],
            2 => ['start' => 'April', 'end' => 'June', 'months' => ['April', 'May', 'June']],
            3 => ['start' => 'July', 'end' => 'September', 'months' => ['July', 'August', 'September']],
            4 => ['start' => 'October', 'end' => 'December', 'months' => ['October', 'November', 'December']]
        ];

        $facilityGroupedItems = [];
        $pharmacyGroupedItems = [];

        $user = User::find($userId);
        $facilities = $user->facilities()->get();
        $pharmacies = $user->pharmacies()->get();

        foreach ($quarters as $quarter => $dates) {
            $startMonth = $dates['start'];
            $endMonth = $dates['end'];
            $months = $dates['months'];

            $currentQuarter = $quarter;

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

                    // Check if the item already exists for this product ID in this quarter
                    $key = $facilityProductId . '_Q' . $currentQuarter;
                    if (!isset($facilityGroupedItems[$key])) {
                        $facilityGroupedItems[$key] = [
                            'total_quantity' => 0,
                            'total_target' => 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => 0,
                            'achieved_value' => 0,
                            'percentage_performance' => 0,
                            'code' => $facilityCode,
                        ];
                    }

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->where(function ($query) use ($startMonth, $endMonth, $currentYear) {
                            $query->whereMonth('date', '>=', Carbon::parse($startMonth)->month)
                                ->whereMonth('date', '<=', Carbon::parse($endMonth)->month)
                                ->whereYear('date', $currentYear);
                        })
                        ->sum('quantity');

                    // Filter target IDs based on facility code
                    $targetIds = Targets::where('code', $facilityCode)
                        ->where('product_id', $facilityProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
                        ->groupBy('code')
                        ->pluck(DB::raw('MIN(id)'));

                    // Get the total target for the current product ID
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where(function ($query) use ($months) {
                            // Filter by the range of months
                            $query->whereIn('month', $months);
                        })
                        ->sum('target');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Calculate metrics
                    $targetValue = $productPrice * $totalTarget;
                    $achievedValue = $productPrice * $totalQuantity;

                    // Update the grouped item with the calculated metrics
                    $facilityGroupedItems[$key]['total_quantity'] += $totalQuantity;
                    $facilityGroupedItems[$key]['total_target'] += $totalTarget;
                    $facilityGroupedItems[$key]['target_value'] += $targetValue;
                    $facilityGroupedItems[$key]['achieved_value'] += $achievedValue;
                    $facilityGroupedItems[$key]['percentage_performance'] = $facilityGroupedItems[$key]['target_value'] != 0 ? ($facilityGroupedItems[$key]['achieved_value'] / $facilityGroupedItems[$key]['target_value']) * 100 : 0;
                }
            }

           // return  $facilityGroupedItems;

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

                    // Check if the item already exists for this product ID in this quarter
                    $key = $pharmacyProductId . '_Q' . $currentQuarter;
                    if (!isset($pharmacyGroupedItems[$key])) {
                        $pharmacyGroupedItems[$key] = [
                            'total_quantity' => 0,
                            'total_target' => 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => 0,
                            'achieved_value' => 0,
                            'percentage_performance' => 0,
                            'code' => $pharmacyCode,
                        ];
                    }

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->where(function ($query) use ($startMonth, $endMonth, $currentYear) {
                            $query->whereMonth('date', '>=', Carbon::parse($startMonth)->month)
                                ->whereMonth('date', '<=', Carbon::parse($endMonth)->month)
                                ->whereYear('date', $currentYear);
                        })
                        ->sum('quantity');

                    // Filter target IDs based on pharmacy code
                    $targetIds = Targets::where('code', $pharmacyCode)
                        ->where('product_id', $pharmacyProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
                        ->groupBy('code')
                        ->pluck(DB::raw('MIN(id)'));

                    // Get the total target for the current product ID
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where(function ($query) use ($months) {
                            // Filter by the range of months
                            $query->whereIn('month', $months);
                        })
                        ->sum('target');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;
                    $targetValue = $productPrice * $totalTarget;
                    $achievedValue = $productPrice * $totalQuantity;

                    // Update the grouped item with the calculated metrics
                    $pharmacyGroupedItems[$key]['total_quantity'] += $totalQuantity;
                    $pharmacyGroupedItems[$key]['total_target'] += $totalTarget;
                    $pharmacyGroupedItems[$key]['target_value'] += $targetValue;
                    $pharmacyGroupedItems[$key]['achieved_value'] += $achievedValue;
                    $pharmacyGroupedItems[$key]['percentage_performance'] = $pharmacyGroupedItems[$key]['target_value'] != 0 ? ($pharmacyGroupedItems[$key]['achieved_value'] / $pharmacyGroupedItems[$key]['target_value']) * 100 : 0;
                }
            }

            $combinedGroupedItems = array_merge($facilityGroupedItems, $pharmacyGroupedItems);
            $groupedItems = [];

            foreach ($combinedGroupedItems as $item) {
                $productCode = $item['product_code'];
                $quarter = substr($item['quarter'], -1); // Extract quarter from existing structure

                // Construct the key for the grouped items array
                $key = $productCode . '_Q' . $quarter;

                // Check if the key already exists
                if (!isset($groupedItems[$key])) {
                    // If not, initialize it with the item's details
                    $groupedItems[$key] = [
                        'product_code' => $productCode,
                        'product' => $item['product'],
                        'quarter' => 'Q' . $quarter,
                        'total_quantity' => $item['total_quantity'],
                        'total_target' => $item['total_target'],
                        'target_value' => $item['target_value'],
                        'achieved_value' => $item['achieved_value'],
                        'percentage_performance' => $item['percentage_performance'],
                    ];
                } else {
                    // If the key already exists, update the values accordingly
                    $groupedItems[$key]['total_quantity'] += $item['total_quantity'];
                    $groupedItems[$key]['total_target'] += $item['total_target'];
                    $groupedItems[$key]['target_value'] += $item['target_value'];
                    $groupedItems[$key]['achieved_value'] += $item['achieved_value'];
                    // You might need to adjust other fields as per your requirement
                }
            }

            // return $combinedItemsByProductId;
        }



        return view('sale.quarterRepItems', [
            'groupedItems' => $groupedItems, // Use the modified $groupedItems array
            'user_id' => $userId,
        ]);

    }

    function clearCaches($cacheKeyPrefix = null) {
        if ($cacheKeyPrefix) {
            $cacheKeys = Cache::get('monthlyRepItems_keys') ?? [];
            foreach ($cacheKeys as $cacheKey) {
                Cache::forget($cacheKey);
            }
            Cache::forget('monthlyRepItems_keys');
        } else {
            Cache::flush();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file)[0];
            // Remove the first row (headings)
            array_shift($data);

            foreach ($data as $row) {
                $excelDateOffset = 25569;

                $excelSerialNumber = $row[5];


                if (is_numeric($excelSerialNumber)) {
                    $unixTimestamp = ($excelSerialNumber - $excelDateOffset) * 86400; // 86400 seconds in a day
                    $excelDate = Carbon::createFromTimestamp($unixTimestamp);
                    $formattedDate = $excelDate->format('Y-m-d');
                } else {
                    return redirect()->back()->with('error', 'Failed Ensure you file is in correct format.');
                }


                // Find the product ID using the product code
                $product = Product::where('code', $row[2])->first();
                if (!$product) {
                    continue; // Skip this row if the product doesn't exist
                }

                // Find users associated with the given customer code in pharmacies or facilities
                $users = User::whereHas('pharmacies', function ($query) use ($row) {
                    $query->where('code', $row[0]);
                })->orWhereHas('facilities', function ($query) use ($row) {
                    $query->where('code', $row[0]);
                })->get();

                // If no users are found, create a sales record for a default user
                if ($users->isEmpty()) {
                    $defaultUser = User::where('email', 'internal.user@healthclassique.com')->first();

                    $saleCreated = false;

                    try {
                        Sale::create([
                            'user_id' => $defaultUser->id,
                            'employee_name' => $defaultUser->first_name . ' ' . $defaultUser->last_name,
                            'customer_code' => $row[0],
                            'customer_name' => $row[1],
                            'product_code' => $row[2],
                            'product_name' => $row[3],
                            'quantity' => $row[4],
                            'amount' => 0, // Set amount accordingly
                            'date' => $formattedDate
                        ]);

                        $saleCreated = true;
                    } catch (\Exception $e) {
                        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
                    }

                    if ($saleCreated) {
                        continue; // Proceed to the next iteration of the loop
                    } else {
                        return redirect()->back()->withErrors(['error' => 'Failed to create sale record.']);
                    }
                }

                // Process each user
                foreach ($users as $user) {
                    // Check if the user has the product in their pharmacy or facility
                    $hasProduct = false;

                    // Check pharmacy
                    if ($user->pharmacies->contains('code', $row[0])) {
                        $pivotRow = $user->pharmacies()->where('code', $row[0])->first();
                        $productIds = $pivotRow->pivot->product_ids ? json_decode($pivotRow->pivot->product_ids, true) : [];
                        if (in_array($product->id, $productIds)) {
                            $hasProduct = true;
                        }
                    }

                    // Check facility
                    if (!$hasProduct && $user->facilities->contains('code', $row[0])) {
                        $pivotRow = $user->facilities()->where('code', $row[0])->first();
                        $productIds = $pivotRow->pivot->product_ids ? json_decode($pivotRow->pivot->product_ids, true) : [];
                        if (in_array($product->id, $productIds)) {
                            $hasProduct = true;
                        }
                    }

                    // If the user has the product, create a sales record
                    if ($hasProduct) {
                        Sale::create([
                            'user_id' => $user->id,
                            'employee_name' => $user->first_name . ' ' . $user->last_name,
                            'customer_code' => $row[0],
                            'customer_name' => $row[1],
                            'product_code' => $row[2],
                            'product_name' => $row[3],
                            'quantity' => $row[4],
                            'amount' => 0, // Set amount accordingly
                            'date' => $formattedDate
                        ]);
                    }
                }
            }

            $this->clearCaches('monthlyRepItems_');
            return redirect()->route('sale.salesrep')->with('success', 'Products uploaded and assigned successfully.');
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }



    public function report(Request $request){
        if ($request->has('filter_month')) {
            $filter_month = $request->get('filter_month');
            // Extract year and month from the selected month
            $year = substr($filter_month, 0, 4);
            $month = substr($filter_month, 5, 2);
            // Set the start date to the first day of the selected month
            $filter_start_date = $year . '-' . $month . '-01';
            // Set the end date to the last day of the selected month
            $filter_end_date = date('Y-m-t', strtotime($filter_start_date));
        } else {
            // If no month is selected, set the date range to the current month
            $filter_start_date = date('Y-m-01');
            $filter_end_date = date('Y-m-t');
        }



        $userTeams = User::pluck('team_id', 'id');
        $uniqueUserIds = Sale::whereBetween('date', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
            ->distinct()
            ->pluck('user_id');

        $salesReps = collect();

        foreach ($uniqueUserIds as $userId) {
            $teamId = $userTeams->get($userId);
            $teamName = Team::find($teamId)->name ?? 'Admin';

            // Include date filter in the Sale query
            $employeeName = Sale::where('user_id', $userId)
                ->whereBetween('date', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
                ->value('employee_name');

            $salesReps->put($userId, ['team_name' => $teamName, 'employee_name' => $employeeName]);
        }

        $data['salesReps'] = $salesReps;
        $data['filter_start_date'] = $filter_start_date;
        $data['filter_end_date'] = $filter_end_date;
        $data['filter_month'] =  $filter_start_date;

        return view('sale.create-report', ['data' => $data]);
    }
    // Display all the sale Representative
    public function salesRep(){
        //$salesReps = Sale::distinct()->pluck('employee_name', 'user_id');
        $userTeams = User::pluck('team_id', 'id');
        $uniqueUserIds = Sale::distinct()->pluck('user_id');
        $salesReps = collect();
        foreach ($uniqueUserIds as $userId) {
            $teamId = $userTeams->get($userId);
            $teamName = Team::find($teamId)->name ?? 'Admin';
            $employeeName = Sale::where('user_id', $userId)->pluck('employee_name')->first();
            $salesReps->put($userId, ['team_name' => $teamName, 'employee_name' => $employeeName]);
        }
        return view('sale.salerep', ['salesReps' => $salesReps]);
    }

    // Display all the facilities of a given sale rep
    public function repFacilities($userId, $productCode)
    {
        $salesFacilities = Sale::where('user_id', $userId)
            ->where('product_code', $productCode)
            ->groupBy('customer_code')
            ->select('customer_code','customer_name', \DB::raw('SUM(quantity) as total_quantity'))
            ->get();

    return view('sale.salefacilities', ['salesFacilities' => $salesFacilities]);

    }

    public  function reportfacilities($userId, $productCode, Request $request)
    {

        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

         if ($userId == $internalUser->id) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;


            $salesFacilities = Sale::where('user_id', $userId)
                ->where('product_code', $productCode)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->groupBy('customer_code')
                ->select('customer_code','customer_name', DB::raw('SUM(quantity) as sales_quantity'))
                ->get();
            //return $salesFacilities;
            foreach ($salesFacilities as $facility) {
                $facilityCode = $facility->customer_code;
                //return $facilityCode;
                $productId = Product::where('code', $productCode)
                    ->value('id');
                //return $productId;
                $targetId = Targets::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('code',$facilityCode)
                    ->value('id');
                // return $targetId;
                $month = strtolower(Carbon::now()->format('F'));
                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $facility->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');
            }
            $combinedReport = $salesFacilities;
            //return $combinedReport;
            // Pass the combined report to the view
            return view('sale.reportfacilities', ['report' => $combinedReport]);

        }else{
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $user = User::find($userId);

            $facilities = $user->facilities()->get();
            $pharmacies = $user->pharmacies()->get();

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
                    $targetId = Targets::where('product_id', $prodcuct_id)
                        ->where('user_id', $userId)
                        ->where('code',$facilityCode)
                        ->value('id');
                    // return $targetId;
                    $month = strtolower(Carbon::now()->format('F'));
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

            if (!empty($pharmaciesWithProduct)) {
                // Your processing logic for pharmacies
                // Process pharmacies
                foreach ($pharmaciesWithProduct as $pharmacy) {
                    $facilityCode = $pharmacy->code;
                    // Get target for the current pharmacy
                    $targetId = Targets::where('product_id', $prodcuct_id)
                        ->where('user_id', $userId)
                        ->where('code',$facilityCode)
                        ->value('id');
                    // return $targetId;
                    $month = strtolower(Carbon::now()->format('F'));
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
            return view('sale.reportfacilities', ['report' => $combinedReport]);
        }

    }




    public  function reportfacilities2($userId, $productCode, Request $request)
    {
        if ($request->has('filter_start_date') && $request->has('filter_end_date')) {
            $filter_start_date = $request->get('filter_start_date');
            $filter_end_date = $request->get('filter_end_date');
        } else {
            $filter_start_date = now()->toDateString();
            $filter_end_date = now()->toDateString();
        }

        $salesFacilities = Sale::where('user_id', $userId)
            ->where('product_code', $productCode)
            ->whereBetween('date', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
            ->groupBy('customer_code')
            ->select('customer_code','customer_name', \DB::raw('SUM(quantity) as total_quantity'))
            ->get();
        //return $userId;
        foreach ($salesFacilities as $facility) {
            $facilityCode = $facility->customer_code;
            //return $facilityCode;
            $productId = Product::where('code', $productCode)
                ->value('id');
            //return $productId;
            $targetId = Targets::where('product_id', $productId)
                ->where('user_id', $userId)
                ->where('code',$facilityCode)
                ->value('id');
            // return $targetId;
            $month = strtolower(Carbon::now()->format('F'));
            $target = TargetMonths::where('target_id',$targetId)
                ->where('month', $month)
                ->value('target');
            //return $target;
            $facility->target = TargetMonths::where('target_id', $targetId)
                ->where('month', $month)
                ->value('target');
        }


        // return $salesFacilities ;


        return view('sale.reportfacilities', [
            'salesFacilities' => $salesFacilities,
            'user_id' => $userId,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date,
        ]);
    }



    public  function quarterReportFacilities($userId, $productCode, Request $request)
    {
        if ($request->has('filter_start_date') && $request->has('filter_end_date')) {
            $filter_start_date = $request->get('filter_start_date');
            $filter_end_date = $request->get('filter_end_date');
        } else {
            $filter_start_date = now()->toDateString();
            $filter_end_date = now()->toDateString();
        }

        $salesFacilities = Sale::where('user_id', $userId)
            ->where('product_code', $productCode)
            ->whereBetween('date', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])
            ->groupBy('customer_code')
            ->select('customer_code','customer_name', \DB::raw('SUM(quantity) as total_quantity'))
            ->get();
        //return $userId;
        foreach ($salesFacilities as $facility) {
            $facilityCode = $facility->customer_code;
            //return $facilityCode;
            $productId = Product::where('code', $productCode)
                ->value('id');
            //return $productId;
            $targetId = Targets::where('product_id', $productId)
                ->where('user_id', $userId)
                ->where('code',$facilityCode)
                ->value('id');
            // return $targetId;
            $month = strtolower(Carbon::now()->format('F'));
            $target = TargetMonths::where('target_id',$targetId)
                ->where('month', $month)
                ->value('target');
            //return $target;
            $facility->target = TargetMonths::where('target_id', $targetId)
                ->where('month', $month)
                ->value('target');
        }
        // return $salesFacilities ;


        return view('sale.quarterReportFacilities', [
            'salesFacilities' => $salesFacilities,
            'user_id' => $userId,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date,
        ]);
    }

    public function reportRepItems($userId, Request $request)
    {

        if ($request->has('filter_month')) {
            $filter_month = $request->get('filter_month');
            // Extract year and month from the selected month
            $year = substr($filter_month, 0, 4);
            $month = substr($filter_month, 5, 2);
            // Set the start date to the first day of the selected month
            $filter_start_date = $year . '-' . $month . '-01';
            // Set the end date to the last day of the selected month
            $filter_end_date = date('Y-m-t', strtotime($filter_start_date));
        } else {
            // If no month is selected, set the date range to the current month
            $filter_start_date = date('Y-m-01');
            $filter_end_date = date('Y-m-t');
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $parent = [];

        // Get unique product codes from sales table for the given user and date range
        $productCodes = Sale::where('user_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->distinct('product_code')
            ->pluck('product_code');

        //return $productCodes;

        // Get the targets for the product codes
        $targetIds = Targets::join('products', 'targets.product_id', '=', 'products.id')
            ->whereIn('products.code', $productCodes)
            ->where('year', $currentYear)
            ->where('user_id', $userId)
            ->pluck('targets.id');
        //return $targetIds;

        $month = strtolower(Carbon::now()->format('F'));
        //Get monthly targets for the selected target IDs
        $targets = TargetMonths::whereIn('target_id', $targetIds)
            ->where('month', $month)
            ->get();

        $totalTarget = 0;

        foreach ($productCodes as $productCode) {
            // Get product details
            $product = Product::where('code', $productCode)->first();

            // Check if product exists
            if ($product) {
                $productId = $product->id;
                //return $productId;

                // Retrieve the target ID for the given product ID
                $targetIds = Targets::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('year', $currentYear)
                    ->pluck('id')->toArray();
                //return $targetIds ;

                // If there are target IDs, calculate the sum of targets for the current product
                if (!empty($targetIds)) {
                    $month = strtolower(Carbon::now()->format('F'));
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->sum('target');

                }
                //return $totalTarget;

                // Initialize total quantity for the current product code
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $productCode)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Target value
                $targetValue = $productPrice * $totalTarget;

                // Achieved Value
                $achievedValue = $productPrice * $totalQuantity;

                // Percentage performance
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Add grouped item to the array
                //return $totalTarget;
                $groupedItems[$productCode] = [
                    'product_code' => $productCode,
                    'product' => $product->name,
                    'total_quantity' => $totalQuantity,
                    'total_target' => $totalTarget,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
                ];
            }
        }

        return view('sale.reportitems', [
            'groupedItems' => $groupedItems, // Use the modified $groupedItems array
            'targets' => $targets,
            'user_id' => $userId,
            'filter_start_date' => $filter_start_date,
            'filter_end_date' => $filter_end_date
        ]);
    }

    // Getting all the Items of a given sell rep in Db
    public function repItems2($userId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get unique product codes from sales table for the given user and date range
        $productCodes = Sale::where('user_id', $userId)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->distinct('product_code')
            ->pluck('product_code');

        //return $productCodes;

// Get the targets for the product codes
        $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
            ->whereIn('products.code', $productCodes)
            ->get()
            ->groupBy('products.code');
        //return $targets;
        $totalTarget = 0;

        foreach ($productCodes as $productCode) {
            // Get product details
            $product = Product::where('code', $productCode)->first();

            // Check if product exists
            if ($product) {
                $productId = $product->id;

                // Retrieve the target ID for the given product ID
                $targetIds = Targets::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->pluck('id')->toArray();
                //return $targetIds ;

                // If there are target IDs, calculate the sum of targets for the current product
                if (!empty($targetIds)) {
                    $month = strtolower(Carbon::now()->format('F'));
                    $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                        ->where('month', $month)
                        ->sum('target');

                }
                //return $totalTarget;

                // Initialize total quantity for the current product code
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $productCode)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');

                // Fetch product price using the relationship
                $productPrice = $product->price;

                // Target value
                $targetValue = $productPrice * $totalTarget;

                // Achieved Value
                $achievedValue = $productPrice * $totalQuantity;

                // Percentage performance
                $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                // Add grouped item to the array
                $groupedItems[$productCode] = [
                    'product_code' => $productCode,
                    'product' => $product->name,
                    'total_quantity' => $totalQuantity,
                    'total_target' => $totalTarget,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
                ];
            }
            //return $groupedItems;
        }
        return view('sale.saleitems', [
            'groupedItems' => $groupedItems, // Use the modified $parent array with variance data
            'targets' => $targets,
            'user_id' => $userId,
        ]);
    }

    public function repItems($userId)
    {
        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

        if ($userId == $internalUser->id){
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');

            //return $productCodes;

            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;

            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $month = strtolower(Carbon::now()->format('F'));
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
                }
            }
            return view('sale.saleitems', [
                'groupedItems' => $groupedItems, // Use the modified $parent array with variance data
                'targets' => $targets,
                'user_id' => $userId,
            ]);

        }else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
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
                    $month = strtolower(Carbon::now()->format('F'));

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Filter target IDs based on facility code
                    $targetIds = Targets::where('code', $facilityCode)
                        ->where('product_id', $facilityProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

                    // Filter target IDs based on pharmacy code
                    $targetIds = Targets::where('code', $pharmacyCode)
                        ->where('product_id', $pharmacyProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
                        ];
                    }
                }
            }

            //return $combinedGroupedItems;

            //return $facilityGroupedItems;
            return view('sale.saleitems', [
                'groupedItems' => $combinedGroupedItems, // Use the modified $parent array with variance data
                'user_id' => $userId,
            ]);
        }
    }

    // Getting all the Items a sale rep has booked Order
    public function salesItems()
    {
        return view('sale.saleitems');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }

    public function fullreport_Index()
    {
        $userTeams = User::pluck('team_id', 'id');
        $uniqueUserIds = Sale::distinct()->pluck('user_id');
        $salesReps = collect();
        foreach ($uniqueUserIds as $userId) {
            $teamId = $userTeams->get($userId);
            $teamName = Team::find($teamId)->name ?? 'Admin';
            $employeeName = Sale::where('user_id', $userId)->pluck('employee_name')->first();
            $salesReps->put($userId, ['team_name' => $teamName, 'employee_name' => $employeeName]);
        }

        return view('sale.fullReport_index',['salesReps'=>$salesReps]);
    }

    public function montlyReport_Index()
    {
        $userTeams = User::pluck('team_id', 'id');
        $uniqueUserIds = Sale::distinct()->pluck('user_id');
        $salesReps = collect();
        foreach ($uniqueUserIds as $userId) {
            $teamId = $userTeams->get($userId);
            $teamName = Team::find($teamId)->name ?? 'Admin';
            $employeeName = Sale::where('user_id', $userId)->pluck('employee_name')->first();
            $salesReps->put($userId, ['team_name' => $teamName, 'employee_name' => $employeeName]);
        }


        return view('sale.monthlyFullReport',['salesReps'=>$salesReps]);
    }


    public function fullRepItems($userId)
    {
        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();
        $groupedItems = [];
        if ($userId == $internalUser->id){
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');

            //return $productCodes;

            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;

            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $month = strtolower(Carbon::now()->format('F'));
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
                }
            }
            return view('sale.fullRepItems', [
                'groupedItems' => $groupedItems,
                'user_id' => $userId,
            ]);
        }else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
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
                    $month = strtolower(Carbon::now()->format('F'));

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Filter target IDs based on facility code
                    $targetIds = Targets::where('code', $facilityCode)
                        ->where('product_id', $facilityProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

                    // Filter target IDs based on pharmacy code
                    $targetIds = Targets::where('code', $pharmacyCode)
                        ->where('product_id', $pharmacyProductId)
                        ->where('year', $currentYear)
                        ->where('user_id', $userId)
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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
                        ];
                    }
                }
            }

            //return $combinedGroupedItems;

            //return $facilityGroupedItems;
            return view('sale.fullRepItems', [
                'groupedItems' => $combinedGroupedItems,
                'user_id' => $userId,
            ]);
        }

    }



    public function monthlyRepItems3($userId)
    {

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $cacheKey = "monthlyRepItems_{$userId}_{$currentMonth}_{$currentYear}";

        $reportData = Cache::remember($cacheKey, 24 * 60, function () use ($userId, $currentMonth, $currentYear) {
            // if the id  is for internal  user
            $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();
            $groupedItems = [];
            if ($userId == $internalUser->id){

                // Get unique product codes from sales table for the given user and date range
                $productCodes = Sale::where('user_id', $userId)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->distinct('product_code')
                    ->pluck('product_code');

                //return $productCodes;

                // Get the targets for the product codes
                $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                    ->whereIn('products.code', $productCodes)
                    ->get()
                    ->groupBy('products.code');
                //return $targets;
                $totalTarget = 0;

                foreach ($productCodes as $productCode) {
                    // Get product details
                    $product = Product::where('code', $productCode)->first();

                    // Check if product exists
                    if ($product) {
                        $productId = $product->id;

                        // Retrieve the target ID for the given product ID
                        $targetIds = Targets::where('product_id', $productId)
                            ->where('user_id', $userId)
                            ->pluck('id')->toArray();
                        //return $targetIds ;

                        // If there are target IDs, calculate the sum of targets for the current product
                        if (!empty($targetIds)) {
                            $month = strtolower(Carbon::now()->format('F'));
                            $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                                ->where('month', $month)
                                ->sum('target');

                        }
                        //return $totalTarget;

                        // Initialize total quantity for the current product code
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('product_code', $productCode)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

                        // Fetch product price using the relationship
                        $productPrice = $product->price;

                        // Target value
                        $targetValue = $productPrice * $totalTarget;

                        // Achieved Value
                        $achievedValue = $productPrice * $totalQuantity;

                        // Percentage performance
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Add grouped item to the array
                        $groupedItems[$productCode] = [
                            'product_code' => $productCode,
                            'product' => $product->name,
                            'total_quantity' => $totalQuantity,
                            'total_target' => $totalTarget,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
                        ];
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

                return  [
                    'groupedItems' => $groupedItems,
                    'user_id' => $userId,
                    'currentMonth' => $currentMonth,
                    'currentYear' =>   $currentYear,
                    'months' => $months,
                    'years' => $years
                ];
            }else {
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
                    if (in_array($monthName, $data['months'])) {
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

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $facilityCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

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
                        $achievedValue = $productPrice * $totalQuantity;
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Store the calculated metrics with facility code as the key in the grouped items array
                        $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                            'total_quantity' => $totalQuantity ?? 0,
                            'total_target' => $totalTarget ?? 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
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

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $pharmacyCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

                        //return  $totalQuantity;

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
                        $achievedValue = $productPrice * $totalQuantity;
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Store the calculated metrics with pharmacy code as the key in the grouped items array
                        $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                            'total_quantity' => $totalQuantity ?? 0,
                            'total_target' => $totalTarget ?? 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
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
                            $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                            // Calculate performance percentage
                            $targetValue = $combinedGroupedItems[$productId]['target_value'];
                            $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                            $combinedGroupedItems[$productId]['percentage_performance'] =
                                $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                        } else {
                            // If the product ID doesn't exist, create a new entry for it
                            $combinedGroupedItems[$productId] = [
                                'total_quantity' => $item['total_quantity'] ?? 0,
                                'total_target' => $item['total_target'] ?? 0,
                                'product_code' => $item['product_code'] ?? null,
                                'product' => $item['product'] ?? null,
                                'target_value' => $item['target_value'] ?? 0,
                                'achieved_value' => $item['achieved_value'] ?? 0,

                                // Calculate performance percentage
                                'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
                return [
                    'groupedItems' => $combinedGroupedItems,
                    'user_id' => $userId,
                    'currentMonth' => $currentMonth,
                    'currentYear' => $currentYear,
                    'months' => $months,
                    'years' => $years
                ];
            }
        });
        return view('sale.monthlyRepItems', $reportData);
    }

    public function monthlyRepItems($userId)
    {
        $cacheKey = 'monthly_rep_items_' . $userId;

        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
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
            $combined = $cachedData;
            toastr()->success('Cached Data');

            return view('sale.monthlyRepItems', [
                'groupedItems' =>  $combined,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' =>   $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();
        $groupedItems = [];
        if ($userId == $internalUser->id){

            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');

            //return $productCodes;

            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;

            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $month = strtolower(Carbon::now()->format('F'));
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
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

            $cacheDuration = Carbon::now()->addDay();
            Cache::put($cacheKey, $groupedItems, $cacheDuration);

            return view('sale.monthlyRepItems', [
                'groupedItems' => $groupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' =>   $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }else {
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
                        ];
                    }
                }
            }
            $cacheDuration = Carbon::now()->addDay();
            Cache::put($cacheKey, $combinedGroupedItems, $cacheDuration);

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
            return view('sale.monthlyRepItems', [
                'groupedItems' =>  $combinedGroupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' =>   $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }

    }

    public function monthlyRepItems2($userId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();
        $groupedItems = [];
        if ($userId == $internalUser->id){

            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');

            //return $productCodes;

            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;

            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $month = strtolower(Carbon::now()->format('F'));
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
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

            return view('sale.monthlyRepItems', [
                'groupedItems' => $groupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' =>   $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }else {
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
            return view('sale.monthlyRepItems', [
                'groupedItems' =>  $combinedGroupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' =>   $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }

    }



    public function monthlyReportFilter(Request $request, $userId)
    {

        $currentMonth = $request->month;
        $currentYear = $request->year;

        $cacheKey = "monthlyReportFilter_{$userId}_{$currentMonth}_{$currentYear}";

        $reportData = Cache::remember($cacheKey, 24 * 60, function () use ($userId, $currentMonth, $currentYear) {
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


            // if the id  is for internal  user
            $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

            if ($userId == $internalUser->id) {



                // Get unique product codes from sales table for the given user and date range
                $productCodes = Sale::where('user_id', $userId)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->distinct('product_code')
                    ->pluck('product_code');



                // Get the targets for the product codes
                $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                    ->whereIn('products.code', $productCodes)
                    ->get()
                    ->groupBy('products.code');
                //return $targets;
                $totalTarget = 0;
                $groupedItems = [];
                foreach ($productCodes as $productCode) {
                    // Get product details
                    $product = Product::where('code', $productCode)->first();

                    // Check if product exists
                    if ($product) {
                        $productId = $product->id;

                        // Retrieve the target ID for the given product ID
                        $targetIds = Targets::where('product_id', $productId)
                            ->where('user_id', $userId)
                            ->pluck('id')->toArray();
                        //return $targetIds ;

                        // If there are target IDs, calculate the sum of targets for the current product
                        if (!empty($targetIds)) {
                            $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                            $month = strtolower($monthName);
                            $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                                ->where('month', $month)
                                ->sum('target');

                        }
                        //return $totalTarget;

                        // Initialize total quantity for the current product code
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('product_code', $productCode)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

                        // Fetch product price using the relationship
                        $productPrice = $product->price;

                        // Target value
                        $targetValue = $productPrice * $totalTarget;

                        // Achieved Value
                        $achievedValue = $productPrice * $totalQuantity;

                        // Percentage performance
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Add grouped item to the array
                        $groupedItems[$productCode] = [
                            'product_code' => $productCode,
                            'product' => $product->name,
                            'total_quantity' => $totalQuantity,
                            'total_target' => $totalTarget,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
                        ];
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
                return  [
                    'groupedItems' => $groupedItems,
                    'user_id' => $userId,
                    'currentMonth' => $currentMonth,
                    'currentYear' => $currentYear,
                    'months' => $months,
                    'years' => $years
                ];
            } else {
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



                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $facilityCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

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
                        $achievedValue = $productPrice * $totalQuantity;
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Store the calculated metrics with facility code as the key in the grouped items array
                        $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                            'total_quantity' => $totalQuantity ?? 0,
                            'total_target' => $totalTarget ?? 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
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

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $pharmacyCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

                        //return  $totalQuantity;

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
                        $achievedValue = $productPrice * $totalQuantity;
                        $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                        // Store the calculated metrics with pharmacy code as the key in the grouped items array
                        $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                            'total_quantity' => $totalQuantity ?? 0,
                            'total_target' => $totalTarget ?? 0,
                            'product_code' => $product_code,
                            'product' => $product->name,
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                            'percentage_performance' => $percentagePerformance,
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
                            $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                            // Calculate performance percentage
                            $targetValue = $combinedGroupedItems[$productId]['target_value'];
                            $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                            $combinedGroupedItems[$productId]['percentage_performance'] =
                                $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                        } else {
                            // If the product ID doesn't exist, create a new entry for it
                            $combinedGroupedItems[$productId] = [
                                'total_quantity' => $item['total_quantity'] ?? 0,
                                'total_target' => $item['total_target'] ?? 0,
                                'product_code' => $item['product_code'] ?? null,
                                'product' => $item['product'] ?? null,
                                'target_value' => $item['target_value'] ?? 0,
                                'achieved_value' => $item['achieved_value'] ?? 0,

                                // Calculate performance percentage
                                'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
                return  [
                    'groupedItems' => $combinedGroupedItems,
                    'user_id' => $userId,
                    'currentMonth' => $currentMonth,
                    'currentYear' => $currentYear,
                    'months' => $months,
                    'years' => $years
                ];
            }
        });
        return view('sale.monthlyRepItems', $reportData);
    }

    public function monthlyReportFilter2(Request $request, $userId)
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


        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

        if ($userId == $internalUser->id) {



            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');



            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;
            $groupedItems = [];
            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $monthName = date('F', mktime(0, 0, 0, $currentMonth, 1));
                        $month = strtolower($monthName);
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
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
            return view('sale.monthlyRepItems', [
                'groupedItems' => $groupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        } else {
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



                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $facilityCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with facility code as the key in the grouped items array
                    $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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

                    // Get the total quantity of sales for the current product ID
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('customer_code', $pharmacyCode)
                        ->where('product_code', $product_code)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    //return  $totalQuantity;

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
                    $achievedValue = $productPrice * $totalQuantity;
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Store the calculated metrics with pharmacy code as the key in the grouped items array
                    $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                        'total_quantity' => $totalQuantity ?? 0,
                        'total_target' => $totalTarget ?? 0,
                        'product_code' => $product_code,
                        'product' => $product->name,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
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
                        $combinedGroupedItems[$productId]['achieved_value'] += $item['achieved_value'] ?? 0;

                        // Calculate performance percentage
                        $targetValue = $combinedGroupedItems[$productId]['target_value'];
                        $achievedValue = $combinedGroupedItems[$productId]['achieved_value'];
                        $combinedGroupedItems[$productId]['percentage_performance'] =
                            $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;
                    } else {
                        // If the product ID doesn't exist, create a new entry for it
                        $combinedGroupedItems[$productId] = [
                            'total_quantity' => $item['total_quantity'] ?? 0,
                            'total_target' => $item['total_target'] ?? 0,
                            'product_code' => $item['product_code'] ?? null,
                            'product' => $item['product'] ?? null,
                            'target_value' => $item['target_value'] ?? 0,
                            'achieved_value' => $item['achieved_value'] ?? 0,

                            // Calculate performance percentage
                            'percentage_performance' => $item['target_value'] != 0 ? ($item['achieved_value'] / $item['target_value']) * 100 : 0,
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
            return view('sale.monthlyRepItems', [
                'groupedItems' => $combinedGroupedItems,
                'user_id' => $userId,
                'currentMonth' => $currentMonth,
                'currentYear' => $currentYear,
                'months' => $months,
                'years' => $years
            ]);
        }
    }

    public function fullRepItems2($userId)
    {
        // if the id  is for internal  user
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

        if ($userId == $internalUser->id){
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Get unique product codes from sales table for the given user and date range
            $productCodes = Sale::where('user_id', $userId)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->distinct('product_code')
                ->pluck('product_code');

            //return $productCodes;

            // Get the targets for the product codes
            $targets = Targets::join('products', 'targets.product_id', '=', 'products.id')
                ->whereIn('products.code', $productCodes)
                ->get()
                ->groupBy('products.code');
            //return $targets;
            $totalTarget = 0;

            foreach ($productCodes as $productCode) {
                // Get product details
                $product = Product::where('code', $productCode)->first();

                // Check if product exists
                if ($product) {
                    $productId = $product->id;

                    // Retrieve the target ID for the given product ID
                    $targetIds = Targets::where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->pluck('id')->toArray();
                    //return $targetIds ;

                    // If there are target IDs, calculate the sum of targets for the current product
                    if (!empty($targetIds)) {
                        $month = strtolower(Carbon::now()->format('F'));
                        $totalTarget = TargetMonths::whereIn('target_id', $targetIds)
                            ->where('month', $month)
                            ->sum('target');

                    }
                    //return $totalTarget;

                    // Initialize total quantity for the current product code
                    $totalQuantity = Sale::where('user_id', $userId)
                        ->where('product_code', $productCode)
                        ->whereMonth('date', $currentMonth)
                        ->whereYear('date', $currentYear)
                        ->sum('quantity');

                    // Fetch product price using the relationship
                    $productPrice = $product->price;

                    // Target value
                    $targetValue = $productPrice * $totalTarget;

                    // Achieved Value
                    $achievedValue = $productPrice * $totalQuantity;

                    // Percentage performance
                    $percentagePerformance = $targetValue != 0 ? ($achievedValue / $targetValue) * 100 : 0;

                    // Add grouped item to the array
                    $groupedItems[$productCode] = [
                        'product_code' => $productCode,
                        'product' => $product->name,
                        'total_quantity' => $totalQuantity,
                        'total_target' => $totalTarget,
                        'target_value' => $targetValue,
                        'achieved_value' => $achievedValue,
                        'percentage_performance' => $percentagePerformance,
                    ];
                }
            }

        }else {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $user = User::find($userId);

            $facilities = $user->facilities()->get();
            $pharmacies = $user->pharmacies()->get();

            $productIds = [];

            // Retrieve product IDs from facilities
            foreach ($facilities as $facility) {
                $productIds = array_merge($productIds, json_decode($facility->pivot->product_ids, true) ?? []);

            }
            // Retrieve product IDs from pharmacies
            foreach ($pharmacies as $pharmacy) {
                $productIds = array_merge($productIds, json_decode($pharmacy->pivot->product_ids, true) ?? []);
            }

            // Remove duplicates from product IDs
            $productIds = array_unique($productIds);

            // Get facility and pharmacy codes
            $facilityCodes = $facilities->pluck('code');
            $pharmacyCodes = $pharmacies->pluck('code');

            // Combine facility and pharmacy codes
            $allCodes = $facilityCodes->merge($pharmacyCodes)->unique();

            // Initialize an empty array to store total quantities and total targets for each product ID
            $groupedItems = [];

            // Loop through each product ID
            foreach ($productIds as $productId) {
                //variables
                $month = strtolower(Carbon::now()->format('F'));
                $product = Product::where('id', $productId)->first();
                $product_name = Product::where('id', $productId)->value('name');
                $product_code = Product::where('id', $productId)->value('code');


                // Get the total quantity of sales for the current product ID
                $totalQuantity = Sale::where('user_id', $userId)
                    ->where('product_code', $product_code)
                    ->whereMonth('date', $currentMonth)
                    ->whereYear('date', $currentYear)
                    ->sum('quantity');


                // Filter target IDs based on facility codes
                $targetIds = Targets::whereIn('code', $allCodes)
                    ->where('product_id', $productId)
                    ->where('year', $currentYear)
                    ->where('user_id', $userId)
                    ->groupBy('code') // Group by code
                    ->pluck(DB::raw('MIN(id)')); // Select the minimum ID for each group

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

                // Store the total quantity and total target for the current product ID
                $groupedItems[$productId] = [
                    'total_quantity' => $totalQuantity ?? 0,
                    'total_target' => $totalTarget ?? 0,
                    'product_code' => $product_code,
                    'product' => $product->name,
                    'target_value' => $targetValue,
                    'achieved_value' => $achievedValue,
                    'percentage_performance' => $percentagePerformance,
                ];
                //return $facilityGroupedItems;
            }
            return view('sale.fullRepItems', [
                'groupedItems' => $groupedItems,
                'user_id' => $userId,
            ]);
        }

    }

    public function fullReportfacilities($userId,$productCode)
    {
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

        if ($userId == $internalUser->id) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;


            $salesFacilities = Sale::where('user_id', $userId)
                ->where('product_code', $productCode)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->groupBy('customer_code')
                ->select('customer_code','customer_name', DB::raw('SUM(quantity) as sales_quantity'))
                ->get();
            //return $salesFacilities;
            foreach ($salesFacilities as $facility) {
                $facilityCode = $facility->customer_code;
                //return $facilityCode;
                $productId = Product::where('code', $productCode)
                    ->value('id');
                //return $productId;
                $targetId = Targets::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('code',$facilityCode)
                    ->value('id');
                // return $targetId;
                $month = strtolower(Carbon::now()->format('F'));
                $target = TargetMonths::where('target_id',$targetId)
                    ->where('month', $month)
                    ->value('target');
                //return $target;
                $facility->target = TargetMonths::where('target_id', $targetId)
                    ->where('month', $month)
                    ->value('target');
            }
            $combinedReport = $salesFacilities;
            //return $combinedReport;
            // Pass the combined report to the view
            return view('sale.fullReportfacilities', ['report' => $combinedReport]);

        }else{
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $user = User::find($userId);

            $facilities = $user->facilities()->get();
            $pharmacies = $user->pharmacies()->get();

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
                        ->pluck(DB::raw('MIN(id)'))
                        ->first();
                    // return $targetId;
                    $month = strtolower(Carbon::now()->format('F'));
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
                        ->pluck(DB::raw('MIN(id)'))
                        ->first();
                    // return $targetId;
                    $month = strtolower(Carbon::now()->format('F'));
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
            return view('sale.fullReportfacilities', ['report' => $combinedReport]);
        }


    }

    public function monthlyReportfacilities($userId,$productCode,$month,$year)
    {
        $currentMonth = $month;
        $currentYear = $year;
        $internalUser = User::where('email', 'internal.user@healthclassique.com')->first();

        if ($userId == $internalUser->id) {

            $salesFacilities = Sale::where('user_id', $userId)
                ->where('product_code', $productCode)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->groupBy('customer_code')
                ->select('customer_code','customer_name', DB::raw('SUM(quantity) as sales_quantity'))
                ->get();
            //return $salesFacilities;
            foreach ($salesFacilities as $facility) {
                $facilityCode = $facility->customer_code;
                //return $facilityCode;
                $productId = Product::where('code', $productCode)
                    ->value('id');
                //return $productId;
                $targetId = Targets::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('code',$facilityCode)
                    ->value('id');
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
            }
            $combinedReport = $salesFacilities;
            //return $combinedReport;
            // Pass the combined report to the view
            return view('sale.monthlyReportfacilities', ['report' => $combinedReport]);

        }else{
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
            return view('sale.monthlyReportfacilities', ['report' => $combinedReport]);
        }


    }

    public function deleteSale()
    {
        $filter_start_date = date('Y-m-d');
        $filter_end_date = date('Y-m-d');
        $sales = [];

        $data['pagetitle'] = "Delete Sales Record";
        $data['start_date'] = $filter_start_date;
        $data['end_date'] = $filter_end_date;
        $data['sales'] = $sales;
        return view('sale.delete_sale',['data'=>$data]);
    }

    public function saleRecord(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($request->has('start_date') && $request->has('end_date')) {
            $filter_start_date = $validatedData['start_date'];
            $filter_end_date = $validatedData['end_date'];
        } else {
            // If no month is selected, set the date range to the current month
            $filter_start_date = null;
            $filter_end_date = null;
        }

        $filteredRecords = Sale::whereBetween('date', [$filter_start_date . ' 00:00:00', $filter_end_date . ' 23:59:59'])->get();

        $data['pagetitle'] = "Delete Sales Record";
        $data['start_date'] = $filter_start_date;
        $data['end_date'] = $filter_end_date;
        $data['sales'] = $filteredRecords;

        return view('sale.delete_record',['data'=>$data]);

    }

    public function delete_filtered_records($start_date, $end_date)
    {
        Sale::whereBetween('date', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->delete();
        $data['pagetitle'] = "Delete Sales Record";

        return redirect()->route('sale.delete_sales',['data'=>$data])->with('success', 'Sales records within the selected date range have been deleted successfully.');
    }
}
