<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CacheMonthlyRepItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-monthly-rep-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $uniqueUserIds = Sale::distinct()->pluck('user_id');
        $users = User::whereIn('id', $uniqueUserIds)
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();


        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        foreach ($users as $user) {
            $userId = $user->id;
            $cacheKey = "monthlyRepItems_{$userId}_{$currentMonth}_{$currentYear}";

            Cache::remember($cacheKey, 60, function () use ($userId, $currentMonth, $currentYear) {
                // Call your function here passing $userId
                $this->monthlyRepItems($userId, $currentMonth, $currentYear);
                Log::info("Done: {$userId}");
            });
        }
    }

    public function monthlyRepItems($userId, $currentMonth, $currentYear)
    {

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
        return $reportData;
    }
}
