<?php

namespace App\Http\Controllers;

use App\Jobs\CalculateMetricsForUserJob;
use App\Models\ClientGpsRecord;
use App\Models\Facility;
use App\Models\GPSRecord;
use App\Models\Pharmacy;
use App\Models\PobImage;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesCall;
use App\Models\SampleSlip;
use App\Models\TargetMonths;
use App\Models\Targets;
use App\Models\Team;
use App\Models\User;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userreport(Request $request)
    {

        if ($request->has('start_date') && $request->has('end_date')) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
        } else {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }

        $current_month_start = date('Y-m-01');
        $current_month_end = date('Y-m-t');

        $user_id = $request->get('user_id');
        //return $current_month_start;
        $date_range_array = [$current_month_start, $current_month_end];

       if ($user_id == "all") {

           $query = SalesCall::with(['facility', 'salescalldetails'])
               ->where('client_type', '=', 'Clinic')
               ->whereBetween('start_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->orderByDesc('id')->get();
           $data['salescalls1'] = $query;


           $query2 = SalesCall::with(['client'])
               ->where('client_type', '=', 'Doctor')
               ->whereBetween('start_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->orderByDesc('id')->get();
           $data['salescalls2'] = $query2;


           $query3 = SalesCall::with(['client', 'salescalldetails'])
               ->where('client_type', '=', 'Pharmacy')
               ->whereBetween('start_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->orderByDesc('id')->get();
           $data['salescalls3'] = $query3;

           $data['book_orders'] = self::getBookOrders($user_id, $date_range_array);
           $data['pharmacy_audits'] = self::getPharmacyAudits($user_id, $date_range_array);
           $data['coverage'] = self::getBookOrders($user_id, $date_range_array);
           $data['call_rate'] = ReportsController::getCallRate($user_id, $date_range_array);

           $data['pagetitle'] = 'All Users Daily Perfomance Report';

           return view('reports.allusers', ['data' => $data]);
       } else {

           $query = SalesCall::with(['facility.location', 'salescalldetails', 'client.locations'])
               ->where(function ($query) use ($start_date, $end_date, $user_id) {
                   $query->where('client_type', 'Clinic')
                       ->orWhere('client_type', 'Doctor')
                       ->orWhere('client_type', 'Pharmacy')
                       ->orWhere('client_type', 'CME')
                       ->orWhere('client_type', 'CME-C')
                       ->orWhere('client_type', 'CME-P');
               })
               ->whereBetween('start_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->where('created_by', $user_id)
               ->orderByDesc('id')
               ->get();

           //return $query;

           $data['salescalls'] = $query;

           $data['coverage'] = ReportsController::getCoverage($user_id, $date_range_array);

           $data['book_orders'] = ReportsController::getBookOrders($user_id, $date_range_array);
           $data['pharmacy_audits'] = ReportsController::getPharmacyAudits($user_id, $date_range_array);
           $data['cme_roundtables'] = ReportsController::getCMEsAndRountables($user_id, $date_range_array);
           //$data['call_rate'] = ReportsController::getCallRate($user_id, $date_range_array);
           $call_rate = ReportsController::getUserTotalCalls($user_id, "current_month", $date_range_array);
           $data['call_rate'] = $call_rate / 20;

           $data['user'] = User::find($user_id);
           $data['pagetitle'] = 'User Daily Perfomance Report';

           $coords = SalesCall::where('created_by', '=', $user_id)
               ->whereBetween('start_time', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->pluck('longitude','latitude')
               ->toArray();

           //return $coords;

           //$data['coordinates2'] = $coords;

           /*
           $data['coordinates'] = [
               ['-1.3239213228225708', '36.84464645385742'],
               ['-1.3162013292312622', '36.83477783203125'],
               // Add more coordinates as needed
           ];
           */
           $current_date = date('Y-m-d');
           $interval = GPSRecord::where('user_id',$user_id)
               ->where('gps_type','Interval')
               ->whereBetween('recorded_at', [$start_date  . ' 00:00:00', $end_date . ' 23:59:59'])
               ->get();
           $calls = GPSRecord::where('user_id',$user_id)
               ->where('gps_type','Calls')
               ->whereBetween('recorded_at', [$start_date  . ' 00:00:00', $end_date . ' 23:59:59'])
               ->get();
           $start = GPSRecord::where('user_id',$user_id)
               ->where('gps_type','Start')
               ->whereBetween('recorded_at', [$start_date  . ' 00:00:00', $end_date . ' 23:59:59'])
               ->first();

           $clients = GPSRecord::where('user_id', $user_id)
               ->where('gps_type', 'Calls')
               ->whereBetween('recorded_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])
               ->get();

           $locations = array();

           foreach ($clients as $client) {
               $client_id = $client->client_id;
               $client_type = $client->client_type;
               $client_name = $client->Client_name;
               $client_location = ClientGpsRecord::where('client_id', $client_id)
                   ->where('client_type', $client_type)
                   ->first(); // Use first() instead of get()

               if ($client_location) { // Check if the location exists
                   $longitude = $client_location->longitude;
                   $latitude = $client_location->latitude;

                   // Add the location to the locations array
                   $locations[] = [
                       'longitude' => $longitude,
                       'latitude' => $latitude,
                       'client_name' =>$client_name
                   ];
               }
           }

          //return $locations;





           $callCounts = [];

           // Initialize call counts for each hour from 07 to 19
           for ($hour = 7; $hour <= 20; $hour++) {
               $callCounts[str_pad($hour, 2, '0', STR_PAD_LEFT)] = 0;
           }

           // Count the calls made in each hour
           foreach ($calls as $call) {
               $recordedHour = date('H', strtotime($call->recorded_at));
               $recordedHourPadded = str_pad($recordedHour, 2, '0', STR_PAD_LEFT);
               if (array_key_exists($recordedHourPadded, $callCounts)) {
                   $callCounts[$recordedHourPadded]++;
               }
           }
           //return $callCounts;

           $data['pagetitle'] = 'GPS Map';
           $data['interval'] = $interval;
           $data['calls'] = $calls;
           $data['start'] = $start;
           $data['locations'] = $locations;

           $data['coordinates'] = [];

           foreach ($coords as $key => $val) {
               $data['coordinates'] [] = [$key, $val];
           }
           //return $data['coordinates'];
           //dd($data['coordinates']);
           $data['start_date'] = $start_date;
           $data['end_date'] = $end_date;
           $data['user_id'] = $user_id;
           //return $data;
           return view('reports.user', ['data' => $data]);
       }

    }


    public function userreportmonth(Request $request)
    {
        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d') . " - " . date('Y-m-d');
        }

        /*
        $dates = explode("-",$filter_date);

        $start_date = trim($dates[0]);
        $end_date = trim($dates[1]);

        $startDate = Carbon::parse($start_date)->format('Y-m-d')." 00:00:00";
         $endDate = Carbon::parse($end_date)->format('Y-m-d')." 23:59:59";
        */

        $start_date = $request->get('filter_date');
        $end_date = $request->get('end_date');
        $date_range_array = [$start_date, $end_date];

        $startDate = $start_date." 00:00:00";


        $endDate = $end_date." 23:59:59";

        $user_id = 'all';
        $data['filter_date'] = $start_date;

        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');

        $month_period = $currentYear."-".$currentMonth."-";



        if ($user_id == "all") {
            $query = SalesCall::with(['facility', 'salescalldetails'])
                ->where('client_type', '=', 'Clinic')
                ->where('start_time', 'like', $month_period.'%')
                ->orderByDesc('id')->get();
            $data['salescalls1'] = $query;


            $query2 = SalesCall::with(['client'])
                ->where('client_type', '=', 'Doctor')
                ->where('start_time', 'like', $month_period.'%')
                ->orderByDesc('id')->get();
            $data['salescalls2'] = $query2;


            $query3 = SalesCall::with(['client', 'salescalldetails'])
                ->where('client_type', '=', 'Pharmacy')
                ->where('start_time', 'like', $month_period.'%')
                ->orderByDesc('id')->get();
            $data['salescalls3'] = $query3;

            $data['book_orders'] = self::getBookOrders($user_id);
            $data['pharmacy_audits'] = self::getPharmacyAudits($user_id);
            //$data['coverage'] = self::getBookOrders($user_id);
            $data['call_rate'] = ReportsController::getCallRate($user_id, $date_range_array);

            $data['pagetitle'] = 'All Users Monthly Perfomance Report';

            return view('reports.allusers', ['data' => $data]);

        }

    }

    public static function getBookOrders($user_id, $start)
	{
        if (is_array($start)) {
            $count = SalesCall::where('pharmacy_order_booked', '=', 'Yes')
                ->whereBetween('sales_calls.created_at', [$start[0], $start[1]])
                ->where('created_by', '=', $user_id)
                ->count();
            $count += PobImage::whereBetween('created_at', [$start[0], $start[1]])
                ->where('user_id', '=', $user_id)
                ->count();
        } else {
            // Get the current month and year
            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            // Count the results
            $count = SalesCall::where('pharmacy_order_booked', '=', 'Yes')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('created_by', '=', $user_id)
                ->count();
            $count += PobImage::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('user_id', '=', $user_id)
                ->count();

        }

        return number_format($count, 0);
	}

	public static function getPharmacyAudits($user_id, $start)
	{
        if (is_array($start)) {
            $count = SalesCall::where('pharmacy_prescription_audit', '=', 'Yes')
                ->whereBetween('sales_calls.created_at', [$start[0], $start[1]])
                ->where('created_by', '=', $user_id)
                ->count();
        } else {
            // Get the current month and year
            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            // Count the results
            $count = SalesCall::where('pharmacy_prescription_audit', '=', 'Yes')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('created_by', '=', $user_id)
                ->count();
        }

        return number_format($count, 0);

	}

    public static function getCMEsAndRountables($user_id, $start)
    {
        if (is_array($start)) {
            $count1 = SalesCall::where('client_type', '=', 'RoundTable')
                ->whereBetween('sales_calls.created_at', [$start[0], $start[1]])
                ->where('created_by', '=', $user_id)
                ->count();

            $count2 = SalesCall::whereIn('client_type', ['CME', 'CME-C', 'CME-P'])
                ->whereBetween('sales_calls.created_at', [$start[0], $start[1]])
                ->where('created_by', '=', $user_id)
                ->count();
        } else {
            // Get the current month and year
            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            // Count the results
            $count1 = SalesCall::where('client_type', '=', 'RoundTable')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('created_by', '=', $user_id)
                ->count();

            $count2 = SalesCall::whereIn('client_type', ['CME', 'CME-C', 'CME-P'])
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->where('created_by', $user_id)
                ->count();
        }

        $count = $count1 + $count2;
        return number_format($count, 0);

    }

    public static function getUserTotalCallsAplusB($user_id, $period = "current_month", $start="23-11"): int {

        if($period == $start) {
            $period = "current_month";
        }

        $closing_sql_stmt = "";

        $sql_statement = "SELECT COUNT(*) as total_sales_calls FROM sales_calls JOIN client_user ON sales_calls.created_by = client_user.user_id WHERE sales_calls.created_by = 3 AND client_user.class = 'B'";

        if ($period == "current_month") {

            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            $sql_statement.=" AND sales_calls.created_at LIKE '$currentYear-$currentMonth%'" . $closing_sql_stmt;

        } else {
            $sql_statement .= "AND sales_calls.created_at BETWEEN '$period[0]' AND '$period[1]'". $closing_sql_stmt;
        }

        $result = DB::select($sql_statement);

        // Count the results
        $Acount = $result[0]->total_sales_calls;


        $sql_statement2 = "SELECT COUNT(*) as total_sales_calls FROM sales_calls JOIN facility_user ON sales_calls.created_by = facility_user.user_id WHERE sales_calls.created_by = $user_id AND facility_user.class = 'A'";

        if ($period == "current_month") {

            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            $sql_statement2.=" AND sales_calls.created_at LIKE '$currentYear-$currentMonth%'" . $closing_sql_stmt;

        } else {
            $sql_statement2 .= "AND sales_calls.created_at BETWEEN '$period[0]' AND '$period[1]'". $closing_sql_stmt;
        }

        $result2 = DB::select($sql_statement2);



        // Count the results
        $Acount2 = $result2[0]->total_sales_calls;

        if ($period == "current_month") {
            $Bcount = SalesCall::join('client_user', 'sales_calls.created_by', '=', 'client_user.user_id')
                ->where('client_user.class', '=', 'B')
                ->whereYear('sales_calls.created_at', $currentYear)
                ->whereMonth('sales_calls.created_at', $currentMonth)
                ->where('sales_calls.created_by', '=', $user_id)
                ->count();
        } else {
            $Bcount = SalesCall::join('client_user', 'sales_calls.created_by', '=', 'client_user.user_id')
                ->where('client_user.class', '=', 'B')
                ->whereBetween('sales_calls.created_at', [$period[0], $period[1]])
                ->where('sales_calls.created_by', '=', $user_id)
                ->count();

        }

        $number = $Acount+$Acount2+$Bcount;

        return $Bcount;
    }

    public static function getCoverage($my_user_id, $start, $date_range_array = null)
	{
        if ($date_range_array) {
            $start = $date_range_array;
        }


        $numbers = self::computeCoverage($my_user_id, $start);

        if ($numbers >= 1) {

            $user_numbers_count = self::getUserNumbers($my_user_id);

            if ($user_numbers_count >= 1) {
                $coverage = ($numbers / $user_numbers_count) * 100;
            } else {
                $coverage = 0;
            }


        } else {
            $coverage = 0;
        }

        return number_format($coverage, 2);

	}

    public static function getUserNumbers($user_id) {

        $sql_statement = "SELECT count(*) AS count FROM `client_user` WHERE user_id = " . $user_id;
        $result = DB::select($sql_statement);
        $count = $result[0]->count;
        //
        $sql_statement2 = "SELECT count(*) AS count FROM `pharmacy_user` WHERE user_id = " . $user_id;
        $result2 = DB::select($sql_statement2);
        $count2 = $result2[0]->count;

        //
        $sql_statement3 = "SELECT count(*) AS count FROM `facility_user` WHERE user_id = " . $user_id;
        $result3 = DB::select($sql_statement3);
        $count3 = $result3[0]->count;

        $total = $count + $count2 + $count3;
        return $total;
    }

    public static function getAdminNumbers($start)
    {
        $filter_date = $start;
        // Get the current month and year
        $startarray = get_month_and_year($start);
        $currentMonth = $startarray[1];
        $currentYear = $startarray[0];

        //$daycount = SalesCall::whereDate('created_at', '=', date('Y-m-d'))->count();
        $daycount = Cache::remember('sales_calls_count_for_today', 7 * 60, function () {
            return SalesCall::whereDate('created_at', '=', date('Y-m-d'))->count();
        });



        $cacheKey = "sales_calls_count_for_{$currentYear}_{$currentMonth}";
        $minutesToExpire = 8 * 60; // Cache duration: 7 hours

        $monthcount = Cache::remember($cacheKey, $minutesToExpire, function () use ($currentMonth, $currentYear) {
            return SalesCall::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count();
        });


        return [$daycount, $monthcount];
    }

    public static function getUserMonthlyTotalCalls($user_id, $period = "current_month"):array {

        $sql_statement = "SELECT sales_calls.client_id, clients.class FROM sales_calls LEFT JOIN clients ON sales_calls.client_id = clients.id WHERE class = 'A'";

        $closing_sql_stmt = " GROUP BY client_id having count(*) > 1";
        if ($period == "current_month") {
            // Get the current month and year
            $currentMonth = now()->format('m');
            $currentYear = now()->format('Y');

            $sql_statement.=" AND sales_calls.created_at LIKE '$currentYear-$currentMonth%'" . $closing_sql_stmt;

        } else {
            $sql_statement .= "AND sales_calls.created_at BETWEEN '$period[0]' AND '$period[1]'". $closing_sql_stmt;
        }


        $result = DB::select($sql_statement);


        // Count the results
        $Acount = count($result);

        if ($period == "current_month") {
            $Bcount = SalesCall::join('clients', 'sales_calls.client_id', '=', 'clients.id')
                ->where('class', '=', 'B')
                ->whereYear('sales_calls.created_at', $currentYear)
                ->whereMonth('sales_calls.created_at', $currentMonth)
                ->where('sales_calls.created_by', '=', $user_id)
                ->count();
        } else {
            $Bcount = SalesCall::join('clients', 'sales_calls.client_id', '=', 'clients.id')
                ->where('class', '=', 'B')
                ->whereBetween('sales_calls.created_at', [$period[0], $period[1]])
                ->where('sales_calls.created_by', '=', $user_id)
                ->count();
        }

        return [$Acount, $Bcount];
    }

    public static function getUserTotalCalls($user_id, $period = "current_month", $start="23-11"): int {


        if ($period == "current_month") {// Get the current month and year

            if (is_array($start)) {
                $count = SalesCall::whereBetween('created_at', [$start[0], $start[1]])
                    ->count();
            } else {
                $startarray = get_month_and_year($start);
                $currentMonth = $startarray[1];
                $currentYear = $startarray[0];

                $count = SalesCall::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->where('created_by', '=', $user_id)
                    ->count();
            }



        } else {
            $count = SalesCall::whereBetween('sales_calls.created_at', [$period[0], $period[1]])
                ->where('sales_calls.created_by', '=', $user_id)
                ->count();
        }

        return $count;
    }


    public static function getUserCallsAplusB(Request $request, $user_id, $period = "current_month", $start="23-11") {

        if($request->filter == "is_on") {

            $startarray = get_month_and_year($start[0]);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];
        } else {
            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];
        }



        /*Getting Coverage Is A Factor of the following Steps

        2. SPLIT DOCTORS AND FACILITIES
        3. FOR EACH GROUP, OF THOSE CALLS WHICH ONES WERE FOR B
        4. OF THE REMAINING CALLS WHICH ONES WERE SEEN MORE THAN ONCE
        */

        //STEP 3

        $sales_calls = SalesCall::whereHas('client', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'B');
            });
        })
            ->where('client_type', '=', 'Doctor')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->count();

        $sales_calls2 = SalesCall::whereHas('facility', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'B');
            });
        })
            ->where('client_type', '=', 'Pharmacy')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->count();

        $sales_calls3 = SalesCall::whereHas('facility', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'B');
            });
        })
            ->where('client_type', '=', 'Clinic')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->count();


        $sales_calls4 = SalesCall::whereHas('client', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'A');
            });
        })
            ->where('client_type', '=', 'Doctor')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->pluck('client_id')->toArray();

        $valuecounts = array_count_values($sales_calls4);
        $multipleoccurencecounts = array_filter($valuecounts, function ($count) {
            return $count > 1;
        });
        $totalcount4 = array_sum($multipleoccurencecounts);

        $sales_calls5 = SalesCall::whereHas('facility', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'A');
            });
        })
            ->where('client_type', '=', 'Pharmacy')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->pluck('client_id')->toArray();

        $valuecounts5 = array_count_values($sales_calls5);
        $multipleoccurencecounts5 = array_filter($valuecounts5, function ($count) {
            return $count > 1;
        });
        $totalcount5 = array_sum($multipleoccurencecounts5);

        $sales_calls6 = SalesCall::whereHas('facility', function ($query) {
            $query->whereHas('users', function ($query) {
                $query->where('class', 'A');
            });
        })
            ->where('client_type', '=', 'Clinic')
            ->whereYear('sales_calls.created_at', $currentYear)
            ->whereMonth('sales_calls.created_at', $currentMonth)
            ->where('sales_calls.created_by', '=', $user_id)
            ->pluck('client_id')->toArray();

        $valuecount6 = array_count_values($sales_calls6);
        $multipleoccurencecounts6 = array_filter($valuecount6, function ($count) {
            return $count > 1;
        });
        $totalcount6 = array_sum($multipleoccurencecounts6);

        return $sales_calls + $sales_calls2 + $sales_calls3+ $totalcount4 + $totalcount5 + $totalcount6;

    }
    public static function getCallRate($user_id, $period = "current_month", $start="2023-11") {


        $sql_statement = "SELECT COUNT(DISTINCT DATE(start_time)) AS mycount FROM `sales_calls` WHERE ";

        if ($user_id != "all") {
            if (isset($user_id)) {
                $sql_statement .= "created_by = $user_id AND ";
            }
        }

        $filter_date = date('Y-m-d');

        if ($period == "current_month") {
            // Get the current month and year
            $startarray = get_month_and_year($start);
            $currentMonth = $startarray[1];
            $currentYear = $startarray[0];

            $sql_statement .= "sales_calls.created_at LIKE '$currentYear-$currentMonth%'";
        } else {
            if (isset($period[0])) {
                $sql_statement .= "sales_calls.created_at BETWEEN '$period[0]' AND '$period[1]'";
            } else {
                $sql_statement .= "sales_calls.created_at LIKE '$filter_date%'";
            }
        }

        //Determine number of days in the month that the sales guy worked

        $result = DB::select($sql_statement);
        $days_worked_in_month = $result[0]->mycount;

        $expected_call_rate = 15 * $days_worked_in_month;

        if ($expected_call_rate > 0) {
            //$numbers = self::getUserMonthlyTotalCalls($user_id, $period);
            $total_numbers_worked_in_month = self::getUserTotalCalls($user_id, "current_month", $start);
            //$total_numbers_worked_in_month = ($numbers[0] + $numbers[1]);

            $call_rate = $total_numbers_worked_in_month / $days_worked_in_month;

            return number_format($call_rate, 2);
        } else {
            return 0;
        }
    }

    public static function computeCoverage($my_user_id, $start) {
        $user = User::find($my_user_id);



        $startarray = get_month_and_year($start);

        if ($startarray === false) {
            return false;
        }
        $currentMonth = $startarray[1];
        $currentYear = $startarray[0];

        //Try catch the line of code below to avoid errors when the user has no facilities

        try {
            $clinics = $user->facilities->unique();
        } catch (\Exception $e) {
            $clinics = [];
        }


        try {
            $doctors = $user->clients->unique();
        } catch (\Exception $e) {
            $doctors = [];
        }

        try {
            $pharmacies = $user->pharmacies->unique();
        } catch (\Exception $e) {
            $pharmacies = [];
        }

        $compute = 0;

        if (is_array($start)) {
            $currentMonth = $start[0];
            $currentYear = $start[1];

            foreach ($clinics as $clinic) {
                $client_id = $clinic->pivot->facility_id;
                $class = $clinic->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class, "range");
                $compute = $compute + $outcome;
            }

            foreach ($doctors as $doctor) {
                $client_id = $doctor->pivot->client_id;
                $class = $doctor->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class, "range");
                $compute = $compute + $outcome;
            }

            foreach ($pharmacies as $pharmacy) {
                $client_id = $pharmacy->pivot->pharmacy_id;
                $class = $pharmacy->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class, "range");
                $compute = $compute + $outcome;
            }
        } else {
            foreach ($clinics as $clinic) {
                $client_id = $clinic->pivot->facility_id;
                $class = $clinic->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class);
                $compute = $compute + $outcome;
            }

            foreach ($doctors as $doctor) {
                $client_id = $doctor->pivot->client_id;
                $class = $doctor->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class);
                $compute = $compute + $outcome;
            }

            foreach ($pharmacies as $pharmacy) {
                $client_id = $pharmacy->pivot->pharmacy_id;
                $class = $pharmacy->pivot->class;
                $outcome = self::computeSalesCalls($client_id, $currentMonth, $currentYear, $class);
                $compute = $compute + $outcome;
            }
        }


        return $compute;
    }

    public static function computeSalesCallsOld($client_id, $currentMonth, $currentYear, $class, $type = "single_start") {
        if ($type == "single_start") {
            $salesCallsCount = SalesCall::where('client_id', $client_id)
                ->whereYear('start_time', $currentYear)
                ->whereMonth('start_time', $currentMonth)
                ->count();
            if ($class == "A") {
                return ($salesCallsCount >= 2) ? 1 : 0;
            } else {
                return ($salesCallsCount >= 1) ? 1 : 0;
            }
        } else {
            $salesCallsCount = SalesCall::where('client_id', $client_id)
                ->wherebetween('start_time', [$currentMonth, $currentYear])
                ->count();
            if ($class == "A") {
                return ($salesCallsCount >= 2) ? 1 : 0;
            } else {
                return ($salesCallsCount >= 1) ? 1 : 0;
            }
        }

        $salesCallsCount = SalesCall::where('client_id', $client_id)
            ->whereYear('start_time', $currentYear)
            ->whereMonth('start_time', $currentMonth)
            ->count();
        if ($class == "A") {
            return ($salesCallsCount >= 2) ? 1 : 0;
        } else {
            return ($salesCallsCount >= 1) ? 1 : 0;
        }
    }

    public static function computeSalesCalls($client_id, $currentMonth, $currentYear, $class, $type = "single_start") {
        // Generate a unique cache key based on the method parameters
        $cacheKey = "sales_calls_{$client_id}_{$currentMonth}_{$currentYear}_{$class}_{$type}";

        // Check if the data is already cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($type == "single_start") {
            $salesCallsCount = SalesCall::where('client_id', $client_id)
                ->whereYear('start_time', $currentYear)
                ->whereMonth('start_time', $currentMonth)
                ->count();
        } else {
            $salesCallsCount = SalesCall::where('client_id', $client_id)
                ->wherebetween('start_time', [$currentMonth, $currentYear])
                ->count();
        }

        $result = ($salesCallsCount >= ($class == "A" ? 2 : 1)) ? 1 : 0;

        // Cache the result with a random expiration time between 21 and 28 days
        $expirationDays = rand(21, 28);
        Cache::put($cacheKey, $result, now()->addDays($expirationDays));

        return $result;
    }

    public function view_prescription_audits(Request $request) {

        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
            $end_date = $request->get('end_date');
            $start_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
            $start_date = $end_date = $filter_date;
        }


        $startDate = $start_date." 00:00:00";
        $endDate = $end_date." 23:59:59";

        $salescalls = SalesCall::where('client_type', 'Pharmacy')
            ->where('pharmacy_prescription_audit', 'Yes')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DAY(created_at)')
            ->get();

        $users = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->get();

        $data['title'] = "View Prescription Audits";
        $data['salescalls'] = $salescalls;
        $data['filter_date'] = $filter_date;
        $data['end_date'] = $end_date;
        $data['users'] = $users;
        return view('salescalls.view-prescription-audits', ['data' => $data]);
    }

    public function view_orders_booked(Request $request) {

        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
            $end_date = $request->get('end_date');
            $start_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
            $start_date = $end_date = $filter_date;
        }


        $startDate = $start_date." 00:00:00";
        $endDate = $end_date." 23:59:59";

        //dd($startDate, $endDate)  ;

        $salescalls = SalesCall::where('pharmacy_order_booked', 'Yes')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DAY(created_at)')
            ->get();

        $pobUploads = PobImage::with('salesperson')->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DAY(created_at)')
            ->get();


        $users = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->get();


        $data['title'] = "View Orders Booked";
        $data['salescalls'] = $salescalls;
        $data['pobUploads'] = $pobUploads;
        $data['filter_date'] = $filter_date;
        $data['end_date'] = $end_date;
        $data['users'] = $users;


        return view('salescalls.view-orders-booked', ['data' => $data]);
    }

    public function view_sample_slips(Request $request) {

        if ($request->has('filter_date')) {
            $filter_date = $request->get('filter_date');
            $end_date = $request->get('end_date');
            $start_date = $request->get('filter_date');
        } else {
            $filter_date = date('Y-m-d');
            $start_date = $end_date = $filter_date;
        }

        $data['filter_date'] = $filter_date;
        $data['end_date'] = $end_date;

        $startDate = $start_date." 00:00:00";
        $endDate = $end_date." 23:59:59";

        $salescalls = SalesCall::with('salesperson')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DAY(created_at)')
            ->get();

        $sample_slip = SampleSlip::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByRaw('DAY(created_at)')
            ->get();

        $users = User::role('user')
            ->whereNotNull('team_id')
            ->where('active_status', 1)
            ->get();

        //return $sample_slip;

        $data['title'] = "View Sample Slips";
        $data['salescalls'] = $salescalls;
        $data['sampleslips'] = $sample_slip;
        $data['users'] = $users;
        return view('salescalls.view-sample-slips', ['data' => $data]);
    }

    /**
     * Function to Return Various Parameters For The Current Month.
     */

    public static function get_parameters_for_current_month() {
        //Get Total SalesCalls For The Current Month

        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');

        $month_year = $currentYear."-".$currentMonth;

        $total_sales_calls = SalesCall::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //For all employees, get their average coverage for the current month

        $users = User::where('active_status', '1')->get();
        $average_coverage = 0;
        $total_users = count($users);

        foreach ($users as $user) {
            $user_id = $user->id;
            $coverage = ReportsController::getCoverage($user_id, $month_year, "23-11");
            $average_coverage = $average_coverage + $coverage;
        }

        //Ensure that the average coverage is in 2 decimal places
        $average_coverage = number_format($average_coverage / $total_users, 2);


        //For all employees, get their average call rate for the current month and year. Return the average in 2 decimal places

        $average_call_rate = 0;
        foreach ($users as $user) {
            $user_id = $user->id;
            $call_rate = ReportsController::getCallRate($user_id, $month_year, "23-11");
            $average_call_rate = $average_call_rate + $call_rate;

        }

        //Ensure that the average call rate is in 2 decimal places
        $average_call_rate = number_format($average_call_rate / $total_users, 2);


        //Get the total number of SalesCalls where pharmacy_order_booked = Yes for this current month
        $total_orders_booked = SalesCall::where('pharmacy_order_booked', 'Yes')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //Get the total number of SalesCalls where pharmacy_prescription_audit = Yes for this current month
        $total_pharmacy_audits = SalesCall::where('pharmacy_prescription_audit', 'Yes')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //Get the total number of CMEs and Roundtables for this current month
        $total_cmes_and_roundtables = SalesCall::where('client_type', 'RoundTable')
            ->orWhere('client_type', 'CME')
            ->orWhere('client_type', 'CME-C')
            ->orWhere('client_type', 'CME-P')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();



        return [$total_sales_calls, $average_coverage, $average_call_rate, $total_orders_booked, $total_pharmacy_audits, $total_cmes_and_roundtables];

    }

    public static function get_parameters_for_filter_month($month) {

        $currentMonth = sprintf('%02d', $month);
        $currentYear = now()->format('Y');

        $month_year = $currentYear."-".$currentMonth;

        $total_sales_calls = SalesCall::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //For all employees, get their average coverage for the current month

        $users = User::where('active_status', '1')->get();
        $average_coverage = 0;
        $total_users = count($users);

        foreach ($users as $user) {
            $user_id = $user->id;
            $coverage = ReportsController::getCoverage($user_id, $month_year, "23-11");
            $average_coverage = $average_coverage + $coverage;
        }

        //Ensure that the average coverage is in 2 decimal places
        $average_coverage = number_format($average_coverage / $total_users, 2);


        //For all employees, get their average call rate for the current month and year. Return the average in 2 decimal places

        $average_call_rate = 0;
        foreach ($users as $user) {
            $user_id = $user->id;
            $call_rate = ReportsController::getCallRate($user_id, $month_year, "23-11");
            $average_call_rate = $average_call_rate + $call_rate;

        }

        //Ensure that the average call rate is in 2 decimal places
        $average_call_rate = number_format($average_call_rate / $total_users, 2);


        //Get the total number of SalesCalls where pharmacy_order_booked = Yes for this current month
        $total_orders_booked = SalesCall::where('pharmacy_order_booked', 'Yes')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //Get the total number of SalesCalls where pharmacy_prescription_audit = Yes for this current month
        $total_pharmacy_audits = SalesCall::where('pharmacy_prescription_audit', 'Yes')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        //Get the total number of CMEs and Roundtables for this current month
        $total_cmes_and_roundtables = SalesCall::where('client_type', 'RoundTable')
            ->orWhere('client_type', 'CME')
            ->orWhere('client_type', 'CME-C')
            ->orWhere('client_type', 'CME-P')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();



        return [$total_sales_calls, $average_coverage, $average_call_rate, $total_orders_booked, $total_pharmacy_audits, $total_cmes_and_roundtables];

    }

    public static function get_teams_sales_for_filter_month($month)
    {
        $teams = Team::all();
        $currentMonth = $month;
        $FormartedcurrentMonth = Carbon::create()->month($month);
        $currentYear = Carbon::now()->year;
        $lastMonth = $FormartedcurrentMonth->subMonth()->month;



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

        $sales = Sale::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        $accumulated_Sales = $sales->sum(function ($sale) {
            $quantity = $sale->quantity;
            $product_code = $sale->product_code;
            $productPrice = Product::where('code', $product_code)->value('price');
            return $productPrice * $quantity;
        });

        $accumulatedSales = round($accumulated_Sales);
        $instance = new self();
        $lastMonthteamTotals = $instance->calculateMetricsForUser($month);
        //Didn't work
//        $month = '03';
//        dispatch(new CalculateMetricsForUserJob($month));

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
                        $monthp = Carbon::createFromFormat('m', $month)->subMonth()->format('F');;

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
                        $monthp = Carbon::createFromFormat('m', $month)->subMonth()->format('F');

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

                //return $teamTotalTargetValue;

                //return  $pharmacyOveralTotals;
                //return  $facilityOveralTotals;
                //return  $overallTotalTargetValue;
                //return   $overallTotalAchievedValue;

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
//        $companyTotalAchievedValue = array_sum(array_column($teamTotals, 'total_achieved_value'));
        $companyTotalAchievedValue = $accumulatedSales;
        $companyPerformance = $companyTotalTargetValue != 0 ? ($companyTotalAchievedValue  / $companyTotalTargetValue) * 100 : 0;

        return $performanceComparison;
    }

    private static function calculateMetricsForUser3($month)
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
    private static function calculateMetricsForUser($month)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::createFromFormat('m', $month)->subMonthNoOverflow()->startOfMonth()->month;

        // Retrieve all teams
        $teams = Team::all();

        $lastMonthteamTotals = [];

        // Iterate through each team
        foreach ($teams as $team) {
            $teamTotalAchievedValue = 0;

            // Get users for the current team
            $users = User::where('team_id', $team->id)->get();

            // Iterate through each user in the team
            foreach ($users as $user) {
                // Initialize total achieved values for the user
                $totalAchievedValue = 0;

                // Get facilities for the current user
                $facilities = $user->facilities()->get();

                // Calculate total achieved value for facilities
                foreach ($facilities as $facility) {
                    $totalAchievedValue += self::calculateTotalAchievedValue($user->id, $facility->code, $currentMonth, $currentYear, $month);
                }

                // Get pharmacies for the current user
                $pharmacies = $user->pharmacies()->get();

                // Calculate total achieved value for pharmacies
                foreach ($pharmacies as $pharmacy) {
                    $totalAchievedValue += self::calculateTotalAchievedValue($user->id, $pharmacy->code, $currentMonth, $currentYear, $month);
                }

                // Accumulate total achieved value for the team
                $teamTotalAchievedValue += $totalAchievedValue;
            }

            // Store team totals
            $lastMonthteamTotals[] = [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'total_achieved_value' => $teamTotalAchievedValue,
            ];
        }

        return $lastMonthteamTotals;
    }

    private static function calculateTotalAchievedValue($userId, $customerCode, $currentMonth, $currentYear, $month)
    {
        // Calculate achieved value for the given user, customer code, and month
        $sales = Sale::where('user_id', $userId)
            ->where('customer_code', $customerCode)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();

        return $sales->sum(function ($sale) {
            $quantity = $sale->quantity;
            $productPrice = Product::where('code', $sale->product_code)->value('price');
            return $productPrice * $quantity;
        });
    }

}
