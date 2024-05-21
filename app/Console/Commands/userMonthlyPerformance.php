<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class userMonthlyPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-monthly-performance';

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
        $users = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();

        foreach ($users as $user) {
            $userId = $user->id;
            $team = $user->team_id;
            $user = User::find($userId);
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;


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
            $totalTargetValue = 0;
            $totalAchievedValue = 0;
            $totalPerformancePercentage = 0;
            foreach ($combinedGroupedItems as $item) {
                // Add the target value and achieved value to the totals
                $totalTargetValue += $item['target_value'];
                $totalAchievedValue += $item['achieved_value'];
            }

            $totalPerformancePercentage = $totalTargetValue != 0 ? ($totalAchievedValue / $totalTargetValue) * 100 : 0;


            $data['performance']= $totalPerformancePercentage;

            // Store the data in cache with the user ID as the key
            Cache::put('sales_data_' . $user->id, $data, now()->addDay());
        }
    }
}
