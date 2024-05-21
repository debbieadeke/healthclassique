<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Sale;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CalculateMetricsForUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $month;

    /**
     * Create a new job instance.
     */
    public function __construct($month)
    {
        $this->month = $month;
    }

    /**
     * Execute the job.
     */
    public function handle($month)
    {
        $teams = Team::all();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::createFromFormat('m', $month)->subMonthNoOverflow()->startOfMonth()->month;


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
                        $lastMonth = Carbon::createFromFormat('m', $month)->subMonth()->format('F');
                        $monthp = strtolower($lastMonth);

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
                            ->where('month', $monthp)
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
                        $lastMonth = Carbon::createFromFormat('m', $month)->subMonth()->format('F');
                        $monthp = strtolower($lastMonth);

                        // Get the total quantity of sales for the current product ID
                        $totalQuantity = Sale::where('user_id', $userId)
                            ->where('customer_code', $pharmacyCode)
                            ->where('product_code', $product_code)
                            ->whereMonth('date', $currentMonth)
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
                            ->where('month', $monthp)
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
}
