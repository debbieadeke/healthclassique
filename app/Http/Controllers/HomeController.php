<?php

namespace App\Http\Controllers;

use App\Jobs\GeneratePharmacyData;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

		if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
        }
		$data['filter_date'] = $filter_date;
		$all_users = User::where('team_id', '!=', NULL)->orderBy('last_name')->get();
        $data['users'] = $all_users;

        $user = Auth::user();
        $logged_in_user_id = $user->id;

        $data['user'] = $user;

        $week_number = get_week_of_this_month();


        if ($request->has('start')) {
            $start = $request->get('start');
        } else {
            $start = date('Y-m');
        }


        $data['start'] = $start;


            $totals = ReportsController::getAdminNumbers($start);
            $data['total_sales_calls'] = number_format($totals[1]);
            $data['today_sales_calls'] = number_format($totals[0]);

            if (count($all_users) >= 1) {
                foreach ($all_users as $user) {
                    //how to get user role
                    $role = $user->role;

                    $id = $user->id;

                    $cacheKey = "get_user_total_calls_for_{$id}_{$start}";
                    $count = Cache::remember($cacheKey, 7 * 60, function () use ($id, $start) {
                        return ReportsController::getUserTotalCalls($id, "current_month", $start);
                    });

                    $full_name = $user->first_name . " " . $user->last_name;

                    $colors = [];

                    $cacheKey = "get_user_coverage_for_{$id}_{$start}";
                    $coverage = Cache::remember($cacheKey, 430, function () use ($id, $start) {
                        return ReportsController::getCoverage($id, $start);
                    });


                    if ($coverage <= 80) {
                        $colors['coverage'] = "red";
                    } else {
                        $colors['coverage'] = "green";
                    }

                    $cacheKey = "get_user_book_orders_for_{$id}_{$start}";

                    $book_orders = Cache::remember($cacheKey, 440, function () use ($id, $start) {
                        return ReportsController::getBookOrders($id, $start);
                    });

                    if ($week_number >= 3) {
                        if ($book_orders <= 36) {
                            $colors['pobs'] = "red";
                        } else {
                            $colors['pobs'] = "green";
                        }
                    } else {
                        $colors['pobs'] = "white";
                    }

                    $cacheKey = "get_user_pharmacy_audits_for_{$id}_{$start}";

                    $pharmacy_audits = Cache::remember($cacheKey, 450, function () use ($id, $start) {
                        return ReportsController::getPharmacyAudits($id, $start);
                    });


                    if ($week_number >= 3) {
                        if ($pharmacy_audits <= 6) {
                            $colors['pa'] = "red";
                        } else {
                            $colors['pa'] = "green";
                        }
                    } else {
                        $colors['pa'] = "white";
                    }

                    $cacheKey = "get_user_cme_roundtables_for_{$id}_{$start}";

                    $cme_roundtables = Cache::remember($cacheKey, 405, function () use ($id, $start) {
                        return ReportsController::getCMEsAndRountables($id, $start);
                    });

                    if ($week_number >= 4) {
                        if ($cme_roundtables <= 1) {
                            $colors['cme'] = "red";
                        } else {
                            $colors['cme'] = "green";
                        }
                    } else {
                        $colors['cme'] = "white";
                    }


                    //$callrate = ReportsController::getCallRate($user->id, "current_month", $start);
                    $callrate = number_format($count / 20, 1);

                    $user_matrix[$id] = array($full_name, $count, $coverage, $book_orders, $pharmacy_audits, $cme_roundtables, $colors, $callrate, $role, $id);

                }
            } else {
                $user_matrix = [];
            }

            $data['user_matrix'] = $user_matrix;

            $cacheKey = "get_user_total_calls_for_{$logged_in_user_id}_{$start}";
            $data['chart_one_total'] = Cache::remember($cacheKey, 7 * 60, function () use ($logged_in_user_id) {
                return SalesCall::where('created_by', $logged_in_user_id)
                    ->count();
            });


        //return   $totalPerformancePercentage;

       //$data['performance'] =  $totalPerformancePercentage;

            $cacheKey = "get_all_threads";
            $data['threads'] = Cache::remember($cacheKey, 1 * 30, function () {
                return Thread::getAllLatest()->get();
            });

        $data['pagetitle'] = "Home";

        $cacheKey = "get_parameters_for_current_month";
        $data['monthly_params'] = Cache::remember($cacheKey, 8 * 60, function () {
            return ReportsController::get_parameters_for_current_month();
        });

        $userId = Auth::id();
        $salesData = Cache::get('sales_data_' . $userId);
        $performance = ($salesData !== null && !empty($salesData['performance'])) ? $salesData['performance'] : 0;

        //return $performance;

        $data['performance'] = $performance;

       //return $totalSales;

        // Retrieve data for the specified month from the cache
        $currentMonth = Carbon::now()->month;
        $key = 'team_totals_' . $currentMonth;
        $teamTotals = Cache::get($key);
        //return $teamTotals;

//        $allTeamTotals = [];
//
//        // Loop through each month
//        for ($month = 1; $month <= 12; $month++) {
//            $key = 'team_totals_' . $month;
//            $teamTotals = Cache::get($key);
//            $allTeamTotals[$month] = $teamTotals;
//        }
//
//        return $allTeamTotals;

//        $teamTotals = Cache::get('team_totals');
//
//        return  $teamTotals;
//        $monthlyTotals = Cache::get('monthlyTotals');


        // Check if companyPerformance is cached
        if (Cache::has('companyPerformance_' . $currentMonth)){
            $key = 'companyPerformance_' . $currentMonth;
            $companyPerformance = Cache::get($key);
        } else {
            // Handle the case when companyPerformance is not cached
            $companyPerformance = null; // or any default value you want
        }
        // Check if totalsales is cached
        if (Cache::has('totalsales_' . $currentMonth)){
            $key = 'totalsales_' . $currentMonth;
            $companyTotalAchievedValue = Cache::get($key);
        } else {
            // Handle the case when totalsales is not cached
            $companyTotalAchievedValue = null; // or any default value you want
        }

        if (Cache::has('monthlyTotals')) {
            $monthlyTotals = Cache::get('monthlyTotals');
        } else {
            $monthlyTotals = [
                "01" => 0,
                "02" => 0,
                "03" => 0,
                "04" => 0,
                "05" => 0,
                "06" => 0,
                "07" => 0,
                "08" => 0,
                "09" => 0,
                "10" => 0,
                "11" => 0,
                "12" => 0
            ];
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

        $currentMonth = Carbon::now()->month;

        $data['monthlyTotals'] = $monthlyTotals;
        $data['months'] =$months;
        $data['currentMonth'] =$currentMonth;
        $data['teamTotals'] = $teamTotals;
        $data['companyPerformance'] = $companyPerformance;
        $data['totalsales'] =  $companyTotalAchievedValue;

        //return  $teamTotals;

        return view('home', ['data' => $data]);
    }


    public function employee_performance_filter(Request $request)
    {
        $month = $request->get('month');
        $currentYear = Carbon::now()->year;
        $firstDateOfMonth = date('Y-m-d', strtotime("$currentYear-$month-01"));
        $formattedMonth = sprintf('%02d', $month);


        $data['filter_date'] = $firstDateOfMonth;
        $all_users = User::where('team_id', '!=', NULL)->orderBy('last_name')->get();
        $data['users'] = $all_users;

        $user = Auth::user();
        $logged_in_user_id = $user->id;

        $data['user'] = $user;

        $week_number = get_week_of_this_month();



        $data['start'] =  $firstDateOfMonth;


        $totals = ReportsController::getAdminNumbers($firstDateOfMonth);
        $data['total_sales_calls'] = number_format($totals[1]);
        $data['today_sales_calls'] = number_format($totals[0]);

        if (count($all_users) >= 1) {
            foreach ($all_users as $user) {
                //how to get user role
                $role = $user->role;

                $id = $user->id;

                $cacheKey = "get_user_total_calls_for_{$id}_{$firstDateOfMonth}";
                $count = Cache::remember($cacheKey, 7 * 60, function () use ($id, $firstDateOfMonth) {
                    return ReportsController::getUserTotalCalls($id, "current_month", $firstDateOfMonth);
                });

                $full_name = $user->first_name . " " . $user->last_name;

                $colors = [];

                $cacheKey = "get_user_coverage_for_{$id}_{$firstDateOfMonth}";
                $coverage = Cache::remember($cacheKey, 430, function () use ($id, $firstDateOfMonth) {
                    return ReportsController::getCoverage($id, $firstDateOfMonth);
                });


                if ($coverage <= 80) {
                    $colors['coverage'] = "red";
                } else {
                    $colors['coverage'] = "green";
                }

                $cacheKey = "get_user_book_orders_for_{$id}_{$firstDateOfMonth}";

                $book_orders = Cache::remember($cacheKey, 440, function () use ($id, $firstDateOfMonth) {
                    return ReportsController::getBookOrders($id, $firstDateOfMonth);
                });

                if ($week_number >= 3) {
                    if ($book_orders <= 36) {
                        $colors['pobs'] = "red";
                    } else {
                        $colors['pobs'] = "green";
                    }
                } else {
                    $colors['pobs'] = "white";
                }

                $cacheKey = "get_user_pharmacy_audits_for_{$id}_{$firstDateOfMonth}";

                $pharmacy_audits = Cache::remember($cacheKey, 450, function () use ($id, $firstDateOfMonth) {
                    return ReportsController::getPharmacyAudits($id, $firstDateOfMonth);
                });


                if ($week_number >= 3) {
                    if ($pharmacy_audits <= 6) {
                        $colors['pa'] = "red";
                    } else {
                        $colors['pa'] = "green";
                    }
                } else {
                    $colors['pa'] = "white";
                }

                $cacheKey = "get_user_cme_roundtables_for_{$id}_{$firstDateOfMonth}";

                $cme_roundtables = Cache::remember($cacheKey, 405, function () use ($id, $firstDateOfMonth) {
                    return ReportsController::getCMEsAndRountables($id, $firstDateOfMonth);
                });

                if ($week_number >= 4) {
                    if ($cme_roundtables <= 1) {
                        $colors['cme'] = "red";
                    } else {
                        $colors['cme'] = "green";
                    }
                } else {
                    $colors['cme'] = "white";
                }


                //$callrate = ReportsController::getCallRate($user->id, "current_month", $start);
                $callrate = number_format($count / 20, 1);

                $user_matrix[$id] = array($full_name, $count, $coverage, $book_orders, $pharmacy_audits, $cme_roundtables, $colors, $callrate, $role, $id);

            }
        } else {
            $user_matrix = [];
        }

        $data['user_matrix'] = $user_matrix;

        $cacheKey = "get_user_total_calls_for_{$logged_in_user_id}_{$firstDateOfMonth}";
        $data['chart_one_total'] = Cache::remember($cacheKey, 7 * 60, function () use ($logged_in_user_id) {
            return SalesCall::where('created_by', $logged_in_user_id)
                ->count();
        });


        //return   $totalPerformancePercentage;

        //$data['performance'] =  $totalPerformancePercentage;

        $cacheKey = "get_all_threads";
        $data['threads'] = Cache::remember($cacheKey, 1 * 30, function () {
            return Thread::getAllLatest()->get();
        });

        $data['pagetitle'] = "Home";


        $cacheKey = "get_parameters_for_current_month";
        Cache::forget($cacheKey);
        $data['monthly_params'] = Cache::remember($cacheKey, 8 * 60, function () use ($request) {
            $month = $request->get('month');
            return ReportsController::get_parameters_for_filter_month($month);
        });

        $userId = Auth::id();
        $salesData = Cache::get('sales_data_' . $userId);
        $performance = ($salesData !== null && !empty($salesData['performance'])) ? $salesData['performance'] : 0;

        //return $performance;

        $data['performance'] = $performance;

        //return  $performance;



        //$teamTotals = Cache::get('team_totals');

        $monthlyTotals = Cache::get('monthlyTotals');

        //$teamTotals = ReportsController::get_teams_sales_for_filter_month($month);
        $currentMonth = $month;
        $key = 'team_totals_' . $currentMonth;
        $teamTotals = Cache::get($key);

        // Check if companyPerformance is cached
        if (Cache::has('companyPerformance_' . $currentMonth)){
            $key = 'companyPerformance_' . $currentMonth;
            $companyPerformance = Cache::get($key);
        } else {
            // Handle the case when companyPerformance is not cached
            $companyPerformance = null; // or any default value you want
        }
        // Check if totalsales is cached
        if (Cache::has('totalsales_' . $currentMonth)){
            $key = 'totalsales_' . $currentMonth;
            $companyTotalAchievedValue = Cache::get($key);
        } else {
            // Handle the case when totalsales is not cached
            $companyTotalAchievedValue = null; // or any default value you want
        }

        if (Cache::has('monthlyTotals')) {
            $monthlyTotals = Cache::get('monthlyTotals');
        } else {
            $monthlyTotals = [
                "01" => 0,
                "02" => 0,
                "03" => 0,
                "04" => 0,
                "05" => 0,
                "06" => 0,
                "07" => 0,
                "08" => 0,
                "09" => 0,
                "10" => 0,
                "11" => 0,
                "12" => 0
            ];
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

        $currentMonth = $request->month;



        $data['monthlyTotals'] = $monthlyTotals;
        $data['months'] =$months;
        $data['currentMonth'] =$currentMonth;
        $data['teamTotals'] = $teamTotals;
        $data['companyPerformance'] = $companyPerformance;
        $data['totalsales'] =  $companyTotalAchievedValue;

        //return  $teamTotals;

        return view('home', ['data' => $data]);
    }

    public function performance(){
        // Get all the teams
        $teams = Team::all();

        // Initialize an empty array to store team IDs and their respective users
        $teamUsers = [];

        // Iterate through each team
        foreach ($teams as $team) {
            // Get the users for the current team
            $users = User::where('team_id', $team->id)->get();
            // Store the team ID and its users in the array
            $teamUsers[$team->id] = $users;
        }


    }


    public function messaging() {
        $data['page_title'] = "Messaging";
        return view('messaging', ['data' => $data]);
    }
}
