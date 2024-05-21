<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateTeamTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-team-totals';

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
        $teams = Team::all();
        $currentYear = Carbon::now()->year;



        // Iterate through each month
        for ($month = 1; $month <= 12; $month++) {

            $teamTotals = [];

            $sales = Sale::whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->get();

            $accumulatedSales = $sales->sum(function ($sale) {
                $quantity = $sale->quantity;
                $product_code = $sale->product_code;
                $productPrice = Product::where('code', $product_code)->value('price');
                return $productPrice * $quantity;
            });

            $accumulatedSales = round($accumulatedSales);

            $lastMonthteamTotals = $this->calculateMetricsForUser($month);



            ///$quarterNumber = $this->getQuarterNumber($month);

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
            //Log::info("Quarter: " .  $quarterNumber);



            // Perform calculations for each team
            foreach ($teams as $team) {
                $users = User::where('team_id', $team->id)->get();
                // Store the team ID and its users in the array

                // Initialize the team's target accumulator
                $teamTotalTargetValue = 0;
                $teamTotalAchievedValue = 0;

                // Iterate through each user in the team
                foreach ($users as $user) {

                    $userId = $user->id;
                    // Get the targets for the current user
                    $facilities = $user->facilities()->get();
                    $pharmacies = $user->pharmacies()->get();
                    //return $facilities;
                    //return $pharmacies ;


                    // Initialize arrays to store grouped items for facilities and pharmacies
                    $facilityGroupedItems = [];
                    $pharmacyGroupedItems = [];
                    $facilityTotals = [];
                    $facilityOveralTotals = [];
                    $totalOverallFacilityTargetValue = 0;
                    $totalOverallFacilityAchievedValue = 0;

                    // Loop through facilities
                    foreach ($facilities as $facility) {
                        // Get facility code and product IDs for this facility
                        $facilityCode = $facility->code;
                        $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

                        $facilityTotalTargetValue = 0;
                        $facilityTotalAchievedValue = 0;
                        // Loop through product IDs for this facility
                        foreach ($facilityProductIds as $facilityProductId) {

                            // Fetch the product information
                            $product = Product::find($facilityProductId);
                            $product_code = $product->code;
                            $month_in_words = Carbon::create(null, $month, 1)->format('F');
                            $month_in_words_lowercase = strtolower($month_in_words);


                            // Get the total quantity of sales for the current product ID
                            $totalQuantity = Sale::where('user_id', $userId)
                                ->where('customer_code', $facilityCode)
                                ->where('product_code', $product_code)
                                ->whereMonth('date', $month)
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
                                ->where('month', $month_in_words_lowercase)
                                ->groupBy(['target_id', 'month'])
                                ->get()
                                ->sum('target');



                            // Fetch product price using the relationship
                            $productPrice = $product->price;

                            // Calculate metrics
                            $targetValue = $productPrice * $totalTarget;
                            $achievedValue = $productPrice * $totalQuantity;

                            // Store the calculated metrics with facility code as the key in the grouped items array
                            $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                                'target_value' => $targetValue,
                                'achieved_value' => $achievedValue,
                            ];
                            // Accumulate total and achieved values for the user
                            $facilityTotalTargetValue += $targetValue;
                            $facilityTotalAchievedValue += $achievedValue;
                        }

                        $facilityTotals[] = [
                            'facility_code' => $facilityCode,
                            'total_target_value' => $facilityTotalTargetValue,
                            'total_achieved_value' => $facilityTotalAchievedValue,
                        ];

                        // Accumulate total target and achieved values for all facilities
                        $totalOverallFacilityTargetValue += $facilityTotalTargetValue;
                        $totalOverallFacilityAchievedValue += $facilityTotalAchievedValue;

                    }
                    // Store total for all facilities
                    $facilityOveralTotals[] = [
                        'total_target_value' => $totalOverallFacilityTargetValue,
                        'total_achieved_value' => $totalOverallFacilityAchievedValue,
                    ];

                    //return  $facilityOveralTotals;


                    $pharmacyTotals = [];
                    $pharmacyOveralTotals = [];
                    $totalOverallPharmacyTargetValue = 0;
                    $totalOverallPharmacyAchievedValue = 0;

                    $allPharmacyTotals = [];

                    // Loop through pharmacies
                    foreach ($pharmacies as $pharmacy) {
                        // Get pharmacy code and product IDs for this pharmacy
                        $pharmacyCode = $pharmacy->code;
                        $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

                        // Initialize total target value and achieved value for the pharmacy
                        $pharmacyTotalTargetValue = 0;
                        $pharmacyTotalAchievedValue = 0;
                        // Loop through product IDs for this pharmacy
                        foreach ($pharmacyProductIds as $pharmacyProductId) {
                            // Fetch the product information
                            $product = Product::find($pharmacyProductId);
                            $product_code = $product->code;
                            $month_in_words = Carbon::create(null, $month, 1)->format('F');
                            $month_in_words_lowercase = strtolower($month_in_words);

                            // Get the total quantity of sales for the current product ID
                            $totalQuantity = Sale::where('user_id', $userId)
                                ->where('customer_code', $pharmacyCode)
                                ->where('product_code', $product_code)
                                ->whereMonth('date', $month)
                                ->whereYear('date', $currentYear)
                                ->sum('quantity');

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
                                ->where('month', $month_in_words_lowercase)
                                ->groupBy(['target_id', 'month'])
                                ->get()
                                ->sum('target');

                            // Fetch product price using the relationship
                            $productPrice = $product->price;


                            // Calculate metrics
                            $targetValue = $productPrice * $totalTarget;
                            $achievedValue = $productPrice * $totalQuantity;

                            // Store the calculated metrics with pharmacy code as the key in the grouped items array
                            $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                                'target_value' => $targetValue,
                                'achieved_value' => $achievedValue,
                            ];

                            // Accumulate total and achieved values for the user
                            $pharmacyTotalTargetValue += $targetValue;
                            $pharmacyTotalAchievedValue += $achievedValue;
                        }
                        // Store the total target and achieved values for the pharmacy


                        $pharmacyTotals[] = [
                            'facility_code' => $pharmacyCode,
                            'total_target_value' => $pharmacyTotalTargetValue,
                            'total_achieved_value' => $pharmacyTotalAchievedValue,
                        ];

                        //return $pharmacyTotals;

                        // Accumulate total target and achieved values for all facilities
                        $totalOverallPharmacyTargetValue += $pharmacyTotalTargetValue;
                        $totalOverallPharmacyAchievedValue += $pharmacyTotalAchievedValue;
                    }


                    $pharmacyOveralTotals[] = [
                        'total_target_value' => $totalOverallPharmacyTargetValue,
                        'total_achieved_value' => $totalOverallPharmacyAchievedValue,
                    ];
                    //return $pharmacyOveralTotals;

                    // Calculate combined totals from pharmacies and facilities
                    $overallTotalTargetValue = $totalOverallPharmacyTargetValue + $totalOverallFacilityTargetValue;
                    $overallTotalAchievedValue = $totalOverallPharmacyAchievedValue + $totalOverallFacilityAchievedValue;

                    $teamTotalTargetValue += $overallTotalTargetValue;
                    $teamTotalAchievedValue += $overallTotalAchievedValue;
                }
                // Accumulate totals for all teams
                $teamTotals[] = [
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'total_target_value' => $teamTotalTargetValue,
                    'total_achieved_value' => $teamTotalAchievedValue,
                ];

                $performanceComparison = [];

                foreach ($teamTotals as $thisMonthTeamTotal) {
                    $teamId = $thisMonthTeamTotal['team_id'];
                    $thisMonthAchievedValue = $thisMonthTeamTotal['total_achieved_value'];
                    $thisMonthTargetValue = $thisMonthTeamTotal['total_target_value'];

                    // Find the corresponding metrics for last month
                    $lastMonthTeamTotal = collect($lastMonthteamTotals)->firstWhere('team_id', $teamId);
                    if ($lastMonthTeamTotal) {
                        $lastMonthAchievedValue = $lastMonthTeamTotal['total_achieved_value'];

                        // Calculate the percentage performance against last month
                        $percentagePerformance = $lastMonthAchievedValue != 0 ? round((($thisMonthAchievedValue - $lastMonthAchievedValue) / $lastMonthAchievedValue) * 100) : 0;

                        // Store the results
                        $performanceComparison[] = [
                            'team_id' => $teamId,
                            'team_name' => $thisMonthTeamTotal['team_name'],
                            'total_target_value' => $thisMonthTargetValue,
                            'total_achieved_value' => $thisMonthAchievedValue,
                            'last_month_total_achieved_value' => $lastMonthAchievedValue,
                            'lastMonth_percentage_performance' => $percentagePerformance,
                        ];
                    }
                }
            }
            $companyTotalTargetValue = array_sum(array_column($teamTotals, 'total_target_value'));
//          $companyTotalAchievedValue = array_sum(array_column($teamTotals, 'total_achieved_value'));
            $companyTotalAchievedValue = $accumulatedSales;
            $companyPerformance = $companyTotalTargetValue != 0 ? ($companyTotalAchievedValue  / $companyTotalTargetValue) * 100 : 0;
            // After calculations for each team, calculate company performance and cache the data
            $this->cacheData($month, $performanceComparison, $companyPerformance, $companyTotalAchievedValue);
        }
    }


    private function calculateMetricsForUser($month)
    {
        $teams = Team::all();
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::createFromDate($currentYear, $month, 1);
        $previousMonth = $currentDate->copy()->subMonth();
        $previousMonthNumber  = $previousMonth->month;

        //Log::info("PreviousMonth: " .  $previousMonthNumber);
        // Get the quarter number for the given month
        //$quarterNumber = $this->getQuarterNumber($previousMonth);

        if ($previousMonthNumber >= 1 && $previousMonthNumber <= 3) {
            $quarterNumber = 1;
        } elseif ($previousMonthNumber >= 4 && $previousMonthNumber <= 6) {
            $quarterNumber =  2;
        } elseif ($previousMonthNumber >= 7 && $previousMonthNumber <= 9) {
            $quarterNumber = 3;
        } elseif ($previousMonthNumber >= 10 && $previousMonthNumber <= 12) {
            $quarterNumber = 4;
        } else {
            // Invalid month number
            return null;
        }


        // Iterate through each team
        foreach ($teams as $team) {
            // Get the users for the current team
            $users = User::where('team_id', $team->id)->get();
            // Store the team ID and its users in the array

            // Initialize the team's target accumulator
            $teamTotalTargetValue = 0;
            $teamTotalAchievedValue = 0;

            // Iterate through each user in the team
            foreach ($users as $user) {

                $userId = $user->id;
                // Get the targets for the current user
                $facilities = $user->facilities()->get();
                $pharmacies = $user->pharmacies()->get();
                //return $facilities;
                //return $pharmacies ;


                // Initialize arrays to store grouped items for facilities and pharmacies
                $facilityGroupedItems = [];
                $pharmacyGroupedItems = [];
                $facilityTotals = [];
                $facilityOveralTotals = [];
                $totalOverallFacilityTargetValue = 0;
                $totalOverallFacilityAchievedValue = 0;

                // Loop through facilities
                foreach ($facilities as $facility) {
                    // Get facility code and product IDs for this facility
                    $facilityCode = $facility->code;
                    $facilityProductIds = json_decode($facility->pivot->product_ids, true) ?? [];

                    $facilityTotalTargetValue = 0;
                    $facilityTotalAchievedValue = 0;
                    // Loop through product IDs for this facility
                    foreach ($facilityProductIds as $facilityProductId) {

                        // Fetch the product information
                        $product = Product::find($facilityProductId);
                        $product_code = $product->code;
                        $lastMonth = Carbon::create(null, $month, 1)->format('F');
                        $lastMonthLowercase = strtolower($lastMonth);

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $facilityCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $previousMonthNumber)
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
                            ->where('month', $lastMonthLowercase)
                            ->groupBy(['target_id', 'month'])
                            ->get()
                            ->sum('target');


                        // Fetch product price using the relationship
                        $productPrice = $product->price;

                        // Calculate metrics
                        $targetValue = $productPrice * $totalTarget;
                        $achievedValue = $productPrice * $totalQuantity;

                        // Store the calculated metrics with facility code as the key in the grouped items array
                        $facilityGroupedItems[$facilityCode][$facilityProductId] = [
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                        ];
                        // Accumulate total and achieved values for the user
                        $facilityTotalTargetValue += $targetValue;
                        $facilityTotalAchievedValue += $achievedValue;
                    }

                    $facilityTotals[] = [
                        'facility_code' => $facilityCode,
                        'total_target_value' => $facilityTotalTargetValue,
                        'total_achieved_value' => $facilityTotalAchievedValue,
                    ];

                    // Accumulate total target and achieved values for all facilities
                    $totalOverallFacilityTargetValue += $facilityTotalTargetValue;
                    $totalOverallFacilityAchievedValue += $facilityTotalAchievedValue;

                }
                // Store total for all facilities
                $facilityOveralTotals[] = [
                    'total_target_value' => $totalOverallFacilityTargetValue,
                    'total_achieved_value' => $totalOverallFacilityAchievedValue,
                ];

                //return  $facilityOveralTotals;


                $pharmacyTotals = [];
                $pharmacyOveralTotals = [];
                $totalOverallPharmacyTargetValue = 0;
                $totalOverallPharmacyAchievedValue = 0;

                $allPharmacyTotals = [];

                // Loop through pharmacies
                foreach ($pharmacies as $pharmacy) {
                    // Get pharmacy code and product IDs for this pharmacy
                    $pharmacyCode = $pharmacy->code;
                    $pharmacyProductIds = json_decode($pharmacy->pivot->product_ids, true) ?? [];

                    // Initialize total target value and achieved value for the pharmacy
                    $pharmacyTotalTargetValue = 0;
                    $pharmacyTotalAchievedValue = 0;
                    // Loop through product IDs for this pharmacy
                    foreach ($pharmacyProductIds as $pharmacyProductId) {
                        // Fetch the product information
                        $product = Product::find($pharmacyProductId);
                        $product_code = $product->code;
                        $lastMonth = Carbon::create(null, $month, 1)->format('F');
                        $lastMonthLowercase = strtolower($lastMonth);

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $pharmacyCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $previousMonthNumber)
                            ->whereYear('date', $currentYear)
                            ->sum('quantity');

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
                            ->where('month',  $lastMonthLowercase)
                            ->groupBy(['target_id', 'month'])
                            ->get()
                            ->sum('target');

                        // Fetch product price using the relationship
                        $productPrice = $product->price;


                        // Calculate metrics
                        $targetValue = $productPrice * $totalTarget;
                        $achievedValue = $productPrice * $totalQuantity;

                        // Store the calculated metrics with pharmacy code as the key in the grouped items array
                        $pharmacyGroupedItems[$pharmacyCode][$pharmacyProductId] = [
                            'target_value' => $targetValue,
                            'achieved_value' => $achievedValue,
                        ];

                        // Accumulate total and achieved values for the user
                        $pharmacyTotalTargetValue += $targetValue;
                        $pharmacyTotalAchievedValue += $achievedValue;
                    }
                    // Store the total target and achieved values for the pharmacy


                    $pharmacyTotals[] = [
                        'facility_code' =>$pharmacyCode,
                        'total_target_value' => $pharmacyTotalTargetValue,
                        'total_achieved_value' => $pharmacyTotalAchievedValue,
                    ];

                    //return $pharmacyTotals;

                    // Accumulate total target and achieved values for all facilities
                    $totalOverallPharmacyTargetValue += $pharmacyTotalTargetValue;
                    $totalOverallPharmacyAchievedValue += $pharmacyTotalAchievedValue;
                }


                $pharmacyOveralTotals[] = [
                    'total_target_value' =>  $totalOverallPharmacyTargetValue,
                    'total_achieved_value' => $totalOverallPharmacyAchievedValue,
                ];
                //return $pharmacyOveralTotals;

                // Calculate combined totals from pharmacies and facilities
                $overallTotalTargetValue = $totalOverallPharmacyTargetValue + $totalOverallFacilityTargetValue;
                $overallTotalAchievedValue = $totalOverallPharmacyAchievedValue + $totalOverallFacilityAchievedValue;

                $teamTotalTargetValue += $overallTotalTargetValue;
                $teamTotalAchievedValue += $overallTotalAchievedValue;


            }
            // Accumulate totals for all teams
            $lastMonthteamTotals[] = [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'total_achieved_value' => $teamTotalAchievedValue,
            ];


        }
        return $lastMonthteamTotals;

    }

    private function getQuarterNumber($month)
    {

        // Convert month to a Carbon instance if it's not already
        $month = is_string($month) ? Carbon::parse($month) : $month;


        // Get the month number (1-12)
        $monthNumber = intval(substr($month, 5, 2));
        //Log::info("Month: " .  $monthNumber);
        // Determine the quarter based on the month number
        if ($monthNumber >= 1 && $monthNumber <= 3) {
            return 1;
        } elseif ($monthNumber >= 4 && $monthNumber <= 6) {
            return 2;
        } elseif ($monthNumber >= 7 && $monthNumber <= 9) {
            return 3;
        } elseif ($monthNumber >= 10 && $monthNumber <= 12) {
            return 4;
        } else {
            // Invalid month number
            return null;
        }
    }

    // Helper function to cache data
    private function cacheData($month, $performanceComparison, $companyPerformance, $companyTotalAchievedValue)
    {
        // Cache the data for the current month
        Cache::put('team_totals_' . $month, $performanceComparison, 24 * 60 * 60);
        Cache::put('companyPerformance_' . $month, $companyPerformance, 24 * 60 * 60);
        Cache::put('totalsales_' . $month, $companyTotalAchievedValue, 24 * 60 * 60);
    }
}



