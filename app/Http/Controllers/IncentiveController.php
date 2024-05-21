<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EpimolMetrics;
use App\Models\PercentageRange;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use App\Models\TierProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\IncentiveMetrics;

class IncentiveController extends Controller
{
    public function tier_products()
    {
       $teams  = Team::with('products')->get();
       $tiers = TierProduct::with('team')->get();
       //return $tiers;
        $data['pagetitle'] = 'Tier Products';
        $data['teams'] = $teams;
        $data['items'] = $tiers;
        return view('incentives.tier_products', ['data' => $data]);
    }

    public function users_incentives()
    {
        $salesReps = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->with('team')
            ->get();

        $data['pagetitle'] = "Incentives for Sales Reps";
        $data['reps'] =  $salesReps;
        return view('incentives.users_incentives',['data'=>$data]);
    }

    public function user_incentives($id)
    {
        $user = User::find($id);
        $cachedData = Cache::get('user_data_' . $id);

        $quarterlyData = $cachedData['quarterlyData'] ?? [];
        $quarters = $cachedData['quarters'] ?? [];
        $selectedQuarter = $cachedData['selectedQuarter'] ?? null;
        $totalIncentivePerQuarter = $cachedData['totalIncentivePerQuarter'] ?? [];
        $totalPercentagePerQuarter = $cachedData['totalPercentagePerQuarter'] ?? [];
        $averagePercentagePerQuarter = $cachedData['averagePercentagePerQuarter'] ?? [];

        // Now you can use $quarterlyData, $quarters, and $selectedQuarter as needed
        // For example, you can assign them to the $data array
        $data['quarterlyData'] = $quarterlyData;
        $data['quarters'] = $quarters;
        $data['selectedQuarter'] = $selectedQuarter;
        $data['totalIncentivePerQuarter'] = $totalIncentivePerQuarter;
        $data['totalPercentagePerQuarter'] =  $totalPercentagePerQuarter;
        $data['averagePercentagePerQuarter'] =  $averagePercentagePerQuarter;
        $data['pagetitle'] = 'Incentives';
        $data['user'] = $user;

        return view('incentives.userIncentive', ['data' => $data]);
    }

    public function store_tier_product(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'team' => 'required|numeric',
                'tier' => 'required|string',
                'products' => 'required|array',
                'products.*' => 'exists:products,id',
            ]);

            // Check if the combination of team and tier already exists
            $existingTier = TierProduct::where('team_id', $request->team)
                ->where('tier', $request->tier)
                ->first();

            if ($existingTier) {
                // If the combination already exists, update the products instead of creating a new record
                $existingTier->products = json_encode($request->products);
                $existingTier->save();
            } else {
                // Otherwise, create a new TierProduct
                $tier = new TierProduct();
                $tier->team_id = $request->team;
                $tier->tier = $request->tier;
                $tier->products = json_encode($request->products);
                $tier->save();
            }

            return redirect()->route('incentive.tier-products')->with('success', 'Tier Products saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Tier Products: ' . $e->getMessage());
        }
    }

    public function salesrep_index()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }

        $cachedData = Cache::get('user_data_' . $user->id);

        $quarterlyData = $cachedData['quarterlyData'] ?? [];
        $quarters = $cachedData['quarters'] ?? [];
        $selectedQuarter = $cachedData['selectedQuarter'] ?? null;
        $totalIncentivePerQuarter = $cachedData['totalIncentivePerQuarter'] ?? [];
        $totalPercentagePerQuarter = $cachedData['totalPercentagePerQuarter'] ?? [];
        $averagePercentagePerQuarter = $cachedData['averagePercentagePerQuarter'] ?? [];

        // Now you can use $quarterlyData, $quarters, and $selectedQuarter as needed
        // For example, you can assign them to the $data array
        $data['quarterlyData'] = $quarterlyData;
        $data['quarters'] = $quarters;
        $data['selectedQuarter'] = $selectedQuarter;
        $data['totalIncentivePerQuarter'] = $totalIncentivePerQuarter;
        $data['totalPercentagePerQuarter'] =  $totalPercentagePerQuarter;
        $data['averagePercentagePerQuarter'] =  $averagePercentagePerQuarter;
        $data['pagetitle'] = 'Incentives';


        return view('incentives.salesrep_index', ['data' => $data]);
    }

    public function incentive_metrics()
    {
        $epimolMetrics = IncentiveMetrics::all();
        $data['pagetitle'] = 'Epimol Metrics';
        $data['epimolMetrics'] =  $epimolMetrics;
        return view('incentives.epimol_metrics', ['data' => $data]);
    }

    public function store_epimol_metrics(Request $request)
    {

        try {
            // Validate the incoming request data
            $request->validate([
                'percent' => 'required|numeric',
                'kpis' => 'required|numeric',
                'tier1' => 'required|numeric',
                'tier2' => 'required|numeric',
                'tier3' => 'required|numeric',
                'total_individual' => 'required|numeric',
            ]);

            $incentive = new IncentiveMetrics();
            $incentive->percentage = $request->percent;
            $incentive->kPIs = $request->kpis;
            $incentive->tier_1 = $request->tier1;
            $incentive->tier_2 = $request->tier2;
            $incentive->tier_3 = $request->tier3;
            $incentive->total_individual = $request->total_individual;
            $incentive->save();

            return redirect()->route('incentive.incentive-metrics')->with('success', 'Epimol Metrics saved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save Epimol Metrics: ' . $e->getMessage());
        }
    }




    public function destroy_epimol_metrics($id)
    {
        try {
            $epimolMetric = EpimolMetrics::findOrFail($id);
            $epimolMetric->delete();
            return redirect()->route('incentive.incentive-metrics')->with('success', 'Epimol Metrics deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete Epimol Metrics: ' . $e->getMessage());
        }
    }

    public function edit_epimol_metrics($id)
    {
        $epimolMetric = EpimolMetrics::find($id);
        $data['epimolMetric'] =  $epimolMetric;
        $data['pagetitle'] = 'Edit Epimol Metrics';
        return view('incentives.edit_epimol_metrics', ['data' => $data]);
    }

    public function update_epimol_metrics(Request $request, $id)
    {
        try {
            $epimolMetric = EpimolMetrics::findOrFail($id);

            $request->validate([
                'percent' => 'required|numeric',
                'kpis' => 'required|numeric',
                'tier1' => 'required|numeric',
                'tier2' => 'required|numeric',
                'tier3' => 'required|numeric',
                'total_individual' => 'required|numeric',
            ]);


            // Update the EPIMOL metric with the new data
            $epimolMetric->update([
             "percentage" => $request->percent,
             "kPIs" => $request->kpis,
             "tier_1" => $request->tier1,
             "tier_2" => $request->tier2,
             "tier_3" => $request->tier3,
             "total_individual" => $request->total_individual,
            ]);

            return redirect()->route('incentive.incentive-metrics')->with('success', 'Epimol Metrics Updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to updated Epimol Metrics: ' . $e->getMessage());
        }
    }
}
