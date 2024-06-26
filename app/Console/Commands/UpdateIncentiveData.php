<?php

namespace App\Console\Commands;

use App\Models\IncentiveMetrics;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\TierProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UpdateIncentiveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-incentive-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user data and store in cache';




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
            $cacheKey = 'pharmacy_data_' . $userId;
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
        }

    }

}
