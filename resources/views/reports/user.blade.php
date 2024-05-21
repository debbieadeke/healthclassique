@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Rep Performance</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-7">
                        <h5 class="card-title fw-semibold mt-2">Sales calls for {{$user->first_name ?? ''}} {{$user->last_name ?? ''}} on  {{\Carbon\Carbon::parse($start_date)->format('d M Y') }}</h5>
                    </div>
                    <div class="col-5">
                        <form method="get" action="{{route('admin.user-report')}}">
                            @csrf
                            <div class="input-group">
                                <input type="date"  id="start_date" class="form-control" name="start_date" value="{{$start_date}}">
                                <input type="date" id="end_date" class="form-control" name="end_date" value="{{$end_date}}">
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <button class="btn btn-light-info text-info font-medium" type="submit">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row">
                    <div class="col-1 "><strong>Coverage:</strong></div>
                    <div class="col-1 border-end">{{$coverage}}</div>
                    <div class="col-1 "><strong>Call Rate:</strong></div>
                    <div class="col-1 border-end">{{$call_rate}}</div>
                    <div class="col-1 "><strong>Daily POBs:</strong></div>
                    <div class="col-1 border-end">{{$book_orders}}</div>
                    <div class="col-2 "><strong>Pxn Audits:</strong></div>
                    <div class="col-1 border-end">{{$pharmacy_audits}}</div>
                    <div class="col-2"><strong>CMEs & RTDs:</strong></div>
                    <div class="col-1">{{$cme_roundtables}}</div>
                </div>
            </div>
        </div>

        <!-- Start Clinic Report -->
        <div class="card p-4">
            <div class="card">
                <div class="card-header p-0 m-0">
                    <div class="row">
                        <div class="col-9">
                            <h5 class="fw-semibold mt-2">Sales Calls List</h5>
                        </div>
                        <div class="col-3">

                        </div>
                    </div>
                </div>
                <div class="card-body p-0">

                    <div class="table-dash p-2">
                        <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                            <thead class="text-dark fs-4">
                            <tr style="font-weight: bold; font-size: 16px">
                                <th class="border-bottom-0" >
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Customer <br> Type</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Customer <br> Name</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Location</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Time of <br>  call </h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Doctor/ <br> Phamtech <br> Seen</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Date</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">View</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($salescalls) > 0)
                                @foreach($salescalls as $salescall)
                                    <tr style="font-size: 13px">
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                        <td class="border-bottom-0"><h6>{{ $salescall->client_type }}</h6></td>
                                        <td class="border-bottom-0">
                                            @if ($salescall->client_type == "Clinic")
                                                <h6 class="fw-semibold mb-1">{{ $salescall->facility->name ?? 'No Hospital' }}</h6>
                                            @elseif ($salescall->client_type == "Doctor")
                                                <h6 class="fw-semibold mb-1">
                                                    {{$salescall->client->first_name ?? 'No Doctor'}} {{$salescall->client->last_name ?? ''}}
                                                </h6>
                                            @elseif ($salescall->client_type == "Pharmacy")
                                                <h6 class="fw-semibold mb-1">{{ $salescall->pharmacy->name ?? 'No Pharmacy' }}</h6>
                                            @elseif ($salescall->client_type == "CME-P")
                                                <h6 class="fw-semibold mb-1">{{ $salescall->pharmacy->name ?? 'No Pharmacy' }}</h6>
                                            @elseif ($salescall->client_type == "CME-C")
                                                <h6 class="fw-semibold mb-1">{{ $salescall->facility->name ?? 'No Hospital' }}</h6>
                                            @elseif ($salescall->client_type == "CME")
                                                <h6 class="fw-semibold mb-1"> {{$salescall->client->first_name ?? 'No Doctor'}} {{$salescall->client->last_name ?? ''}}</h6>
                                            @endif

                                        </td>
                                        <td class="border-bottom-0">
                                            <h6>
                                                @if ($salescall->client_type == "Clinic")
                                                    <span class="fw-normal">{{ $salescall->facility ? ($salescall->facility->location ? $salescall->facility->location->name : 'No location available') : 'Client not available' }}</span>
                                                @elseif ($salescall->client_type == "Doctor")
                                                    <span class="fw-normal">{{ $salescall->client ? ($salescall->client->locations ? $salescall->client->locations->name : 'No location available') : 'Client not available' }}</span>
                                                @elseif ($salescall->client_type == "Pharmacy")
                                                    <span class="fw-normal">{{ $salescall->pharmacy ? ($salescall->pharmacy->location ? $salescall->pharmacy->location->name : 'No location available') : 'Client not available' }}</span>
                                                @elseif ($salescall->client_type == "CME-P")
                                                    <span class="fw-normal">{{ $salescall->pharmacy ? ($salescall->pharmacy->location ? $salescall->pharmacy->location->name : 'No location available') : 'Client not available' }}</span>
                                                @elseif ($salescall->client_type == "CME-C")
                                                    <span class="fw-normal">{{ $salescall->facility ? ($salescall->facility->location ? $salescall->facility->location->name : 'No location available') : 'Client not available' }}</span>
                                                @elseif ($salescall->client_type == "CME")
                                                    <span class="fw-normal">{{ $salescall->client ? ($salescall->client->locations ? $salescall->client->locations->name : 'No location available') : 'Client not available' }}</span>
                                                @endif
                                            </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-normal">
                                                {{
                                                    \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                                }} -
                                                {{
                                                    \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                                }}

                                            </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            @if ($salescall->client_type == "Doctor")
                                                <h6 class="fw-normal mb-1">1</h6>
                                            @else
                                                <h6 class="fw-normal mb-1">{{count($salescall->salescalldetails)}}</h6>
                                            @endif
                                        </td>
                                        <td class="border-bottom-0">
                                                <?php
                                                // Convert the created_at date to a Carbon instance
                                                $createdAt = \Carbon\Carbon::parse($salescall->created_at);

                                                // Get the day of the month with ordinal suffix
                                                $dayWithSuffix = $createdAt->format('jS');

                                                // Format the date as "2nd March"
                                                $formattedDate = $dayWithSuffix . ' ' . $createdAt->format('F');
                                                ?>

                                            <h6 class="fw-normal mb-1">{{ $formattedDate }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @if ($salescall->client_type == "Clinic")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show-hospital', ['salescall' => $salescall->id]) }}"><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i> &nbsp; View Sales Call</a>
                                                    @elseif ($salescall->client_type == "Doctor")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show', ['salescall' => $salescall->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i> View Sales Call</a>
                                                    @elseif ($salescall->client_type == "Pharmacy")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show-pharmacy', ['salescall' => $salescall->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i>View Sales Call</a>
                                                    @elseif ($salescall->client_type == "CME-P")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show-pharmacy', ['salescall' => $salescall->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i>View Sales Call</a>
                                                    @elseif ($salescall->client_type == "CME-C")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show', ['salescall' => $salescall->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i>View Sales Call</a>
                                                    @elseif ($salescall->client_type == "CME")
                                                        <a class="dropdown-item" href="{{ route('salescalls.show-cme', ['salescall' => $salescall->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i>View Sales Call</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No sales calls on selected date</h6></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Clinic Report -->

            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fw-semibold">GPS Map</h5>
                    </div>
                    <div class="card-body">
                        <div class="content__table visit_time_line">
                            <div class="list-table-content">
                                <div class="table-control-header">
                                    <h4>Visit Timeline</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table nonebulk" id="visit_timeline_table">
                                        <colgroup>
                                            <col class="first_visit_css">
                                            <col class="last_visit_css">
                                            <col class="distance_css">
                                        </colgroup>
                                        <thead>
                                        <tr id="visit_per_hour_header_js"><th class="first_visit_css">First Visit</th>
                                            <th class="last_visit_css">Last Visit</th>
                                            <th class="distance_css">Distance</th>
                                            @foreach(range(7, 20) as $hour)
                                                <th class="bg-gray" colspan="2">{{ $hour }}</th>
                                            @endforeach</tr>
                                        </thead>
                                        <tbody>
                                        <tr id="visit_per_hour_js">
                                            <?php
                                            $firstCallTime = '';
                                            $lastCallTime = '';
                                            if (!empty($calls) && count($calls) > 0) {
                                                // Extract the time for the first call
                                                $firstCallTime = date('h:i A', strtotime($calls[0]->start_time));

                                                // Extract the time for the last call
                                                $lastCallIndex = count($calls) - 1;
                                                $lastCallTime = date('H:i A', strtotime($calls[$lastCallIndex]->start_time));
                                            }
                                            if (!empty($interval)) {
                                                // Find the index of the first interval after 7:30 AM
                                                $firstIntervalIndex = null;
                                                foreach ($interval as $index => $intervalItem) {
                                                    $recordedTime = strtotime($intervalItem->recorded_at);
                                                    if (date('H:i', $recordedTime) >= '07:30') {
                                                        $firstIntervalIndex = $index;
                                                        break;
                                                    }
                                                }

                                                // Find the index of the last interval before 6:30 PM
                                                $lastIntervalIndex = null;
                                                $intervalCount = count($interval);
                                                for ($i = $intervalCount - 1; $i >= 0; $i--) {
                                                    $recordedTime = strtotime($interval[$i]->recorded_at);
                                                    if (date('H:i', $recordedTime) <= '18:30') {
                                                        $lastIntervalIndex = $i;
                                                        break;
                                                    }
                                                }

                                                // Calculate total distance covered between the first and last intervals
                                                $totalDistance = 0;
                                                for ($i = $firstIntervalIndex; $i < $lastIntervalIndex; $i++) {
                                                    $distance = calculateDistance($interval[$i]->latitude, $interval[$i]->longitude, $interval[$i + 1]->latitude, $interval[$i + 1]->longitude);
                                                    $totalDistance += $distance;
                                                }

                                                // Format total distance with one decimal place and indicate units
                                                $formattedTotalDistance = number_format($totalDistance, 1) . " km";
                                            }
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
                                            ?>
                                            <td class="first_visit_js first_visit_css">
                                                <span class="mt-2">{{ $firstCallTime }}</span>
                                            </td>
                                            <td class="last_visit_js last_visit_css">{{ $lastCallTime }}</td>
                                            <td class="text-center distance_js distance_css">{{ $formattedTotalDistance }}</td>

                                            @foreach(range(7, 20) as $hour)
                                                    <?php
                                                    $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);
                                                    $count = isset($callCounts[$hourKey]) ? $callCounts[$hourKey] : 0; // Ensure count is available for each hour
                                                    $circleClass = $count > 0 ? 'green-circle' : '';
                                                    ?>
                                                <th class="bg-gray visit-count-css" colspan="2">
                                                    <div class="visit-count-wrapper">
                                                        <div class="count-css {{ $circleClass }}">{{ $count }}</div>
                                                    </div>
                                                </th>
                                        @endforeach

                                        <tr class="gps_info_css" id="gps_info_js"><td colspan="2">
                                                <span class="text-secondary">Fake GPS:</span>
                                                <span class="text-secondary">Not Detected</span>
                                            </td>
                                            <td class="text-secondary">GPS Status</td>
                                            @foreach(range(7, 20) as $hour)
                                                    <?php
                                                    // Get the count for the current hour
                                                    $hourKey = str_pad($hour, 2, '0', STR_PAD_LEFT);
                                                    $count = $callCounts[$hourKey] ?? 0; // Ensure count is available for each hour
                                                    $circleClass = $count > 0 ? 'green-circle' : '';

                                                    // Get the start and end timestamps for the current hour
                                                    $startTime = strtotime(date('Y-m-d H:00:00', strtotime("{$hour}:00:00")));
                                                    $endTime = strtotime(date('Y-m-d H:59:59', strtotime("{$hour}:00:00")));

                                                    // Check if any GPS data exists within the current hour
                                                    $hasGPSData = false;
                                                    foreach ($interval as $data) {
                                                        $timestamp = strtotime($data['recorded_at']);
                                                        if ($timestamp >= $startTime && $timestamp <= $endTime) {
                                                            $hasGPSData = true;
                                                            break;
                                                        }
                                                    }

                                                    // Determine GPS icon class based on GPS data availability
                                                    $gpsIconClass = $hasGPSData ? 'fa-map-marker-alt text-success' : 'fa-map-marker-alt text-danger';
                                                    ?>
                                                <td class="icon" colspan="2">
                                                    <i class="fas {{ $gpsIconClass }}"></i>
                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="map_section" id="Map">
                                    <div class="row">
                                        <div class="col-4" style="height: 400px; overflow-y: auto;">
                                            <div id="clock-in-js"></div>
                                            <div id="visit-list-js">
                                                <div class="card custom-card">
                                                    <div class="card-body">
                                                        <article>
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <div class="center">
                                                                        @if(isset($start) && !empty($start))
                                                                            <span><h6>{{ date('H:i', strtotime($start['recorded_at'])) }}</h6></span>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="flex-row">
                                                                        <h6><i class="fa fa-location-dot"></i>
                                                                            <span class="text-secondary">Start</span></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    </div>
                                                </div>

                                                <?php
                                                // Initialize variables to store total distance and total time
                                                $totalDistance = 0;
                                                $totalTime = 0;


                                                function calculateDistance($lat1, $lon1, $lat2, $lon2) {
                                                    $earthRadius = 6371; // Radius of the Earth in kilometers

                                                    $latDifference = deg2rad($lat2 - $lat1);
                                                    $lonDifference = deg2rad($lon2 - $lon1);

                                                    $a = sin($latDifference / 2) * sin($latDifference / 2) +
                                                        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                                                        sin($lonDifference / 2) * sin($lonDifference / 2);
                                                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

                                                    $distance = $earthRadius * $c; // Distance in kilometers

                                                    return $distance;
                                                }
                                                ?>
                                                @foreach($calls as $index => $call)
                                                    @if($index == 0 && !empty($start) && !empty($call))
                                                            <?php
                                                            // Calculate distance between start and first call
                                                            $distance = calculateDistance($start->latitude, $start->longitude, $call->latitude, $call->longitude);
                                                            // Calculate time difference between start and first call
                                                            $timeDiff = strtotime($call->recorded_at) - strtotime($start->recorded_at);
                                                            $timeDiffInMinutes = round($timeDiff / 60);
                                                            ?>
                                                            <!-- Display distance and time between start and first call -->
                                                        <div class="row pb-1">
                                                            <div class="col">
                                                                <div class="move-detail">
                                                                    <h6><i class="fa-solid fa-route"></i>
                                                                        <span class="text-secondary">{{ number_format($distance, 1) }}km ({{ $timeDiffInMinutes }} min)</span>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <!-- Display individual call card -->
                                                    <div class="card custom-card mb-3" onclick="centerMapOnPin(map, '{{ $call->latitude }}', '{{ $call->longitude }}')">
                                                        <div class="card-body">
                                                            <article>
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto">
                                                                        <div class="center">
                                                                            <h6>
                                                                                <span>{{ date('H:i', strtotime($call->start_time)) }}</span><br>
                                                                                <span class="text-secondary">({{ round((strtotime($call->end_time) - strtotime($call->start_time)) / 60) }} min)</span><br>
                                                                                <span>{{ date('H:i', strtotime($call->end_time)) }}</span>
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="flex-row">
                                                                            <h6><a>{{ $call->Client_name }}</a></h6>
                                                                        </div>
                                                                        <div class="flex-row">
                                                                            <h6>
                                                                                <i class="fa fa-location-dot"></i>
                                                                                <span class="text-secondary">#{{ $loop->iteration }}</span>
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </article>
                                                        </div>
                                                    </div>

                                                    <!-- Calculate distance and time for subsequent calls -->
                                                    @if($index < count($calls) - 1)
                                                            <?php
                                                            // Calculate distance between current call and the next call
                                                            $nextCall = $calls[$index + 1];
                                                            $nextDistance = calculateDistance($call->latitude, $call->longitude, $nextCall->latitude, $nextCall->longitude);

                                                            // Calculate time difference between current call and the next call
                                                            $nextTimeDiff = strtotime($nextCall->recorded_at) - strtotime($call->recorded_at);
                                                            $nextTimeDiffInMinutes = round($nextTimeDiff / 60);
                                                            ?>
                                                            <!-- Display distance and time between current call and the next call -->
                                                        <div class="row pb-1">
                                                            <div class="col">
                                                                <div class="move-detail">
                                                                    <h6><i class="fa-solid fa-route"></i>
                                                                        <span class="text-secondary">{{ number_format($nextDistance, 1) }}km ({{ $nextTimeDiffInMinutes }} min)</span>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                            </div>
                                            <div id="clock-out-js"></div>
                                        </div>
                                        <div class="visit_map col-8" data-country="Kenya" id="sales_map" style="position: relative; overflow: hidden;">
                                            <div id="map" style="height: 400px;"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

            <!-- Vue.js framework script -->
            <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
            <!-- GMapVue plugin script -->
            <script src="https://cdn.jsdelivr.net/npm/gmap-vue@2"></script>
            <script>
                function getUserLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition);
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                }
                function getUserDetails() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(showPosition);
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                    var AddDoctorRowDiv = document.getElementById("AddDoctorRow");
                    const selectElement = document.getElementById("client_id");

                    if (selectElement.value == "add_new") {
                        AddDoctorRowDiv.style.display = "flex";
                        document.getElementById("speciality_div").style.display = "none";
                        document.getElementById("class_div").style.display = "none";
                    } else {
                        var inputs = AddDoctorRowDiv.querySelectorAll('input');
                        var selects = AddDoctorRowDiv.querySelectorAll('select');

                        inputs.forEach(function(input) {
                            input.value = '';
                        });

                        selects.forEach(function(select) {
                            select.selectedIndex = 0;
                        });

                        AddDoctorRowDiv.style.display = "none";

                        document.getElementById("speciality_div").style.display = "flex";
                        document.getElementById("class_div").style.display = "flex";


                        const selectedIndex = selectElement.selectedIndex;
                        const selectedOption = selectElement.options[selectedIndex];

                        const extraInfo = selectedOption.getAttribute("data-extra-info");
                        const inputElement = document.getElementById("speciality");

                        const classInfo = selectedOption.getAttribute("data-extra-class");
                        const inputElement2 = document.getElementById("myclass");

                        // Check if the element is found
                        if (inputElement) {
                            // Set the value of the input field
                            inputElement.value = extraInfo;
                        }

                        // Check if the element is found
                        if (inputElement2) {
                            // Set the value of the input field
                            inputElement2.value = classInfo;
                        }
                    }
                }

                function showPosition(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("longitude").value = longitude;
                    document.getElementById("latitude").value = latitude;
                }
            </script>
            <script>
                var map;

                // Initialize the map
                function initMap() {
                    // Create a new map object
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 14 // Adjust the zoom level as needed
                    });

                    // Set default coordinates if there is no data available
                    var defaultLocation = { lat: -1.325098, lng: 36.8307533 };

                    // Fetch the interval data from the backend (replace this with your actual data retrieval logic)
                    var intervalData = @json($interval);

                    // Fetch the calls data from the backend (replace this with your actual data retrieval logic)
                    var callsData = @json($calls);

                    // Fetch the locations data from the backend
                    var locationsData = @json($locations);

                    // Create arrays to hold the path coordinates and markers
                    var pathCoordinates = [];
                    var markers = [];

                    // Function to add markers to the map
                    function addMarkers(data, iconPath, isCall) {
                        data.forEach(function(point) {
                            var latLng = { lat: parseFloat(point.latitude), lng: parseFloat(point.longitude) };
                            pathCoordinates.push(latLng);

                            var contentString;
                            if (isCall) {
                                contentString = '<div><strong>Client Name: </strong>' + point.Client_name + '<br><strong>Recorded at: </strong>' + point.recorded_at + '</div>';
                            } else {
                                contentString = '<div><strong>Recorded at: </strong>' + point.recorded_at + '</div>';
                            }
                            var marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                                title: isCall ? 'Client: ' + point.Client_name : 'Recorded at: ' + point.recorded_at, // Display title accordingly
                                icon: iconPath
                            });

                            // Add click event listener to display InfoWindow
                            marker.addListener('click', function() {
                                var infoWindow = new google.maps.InfoWindow({
                                    content: contentString
                                });
                                infoWindow.open(map, marker);
                            });

                            markers.push(marker);
                        });
                    }

                    if (locationsData.length > 0) {
                        // Create an empty bounds object
                        var bounds = new google.maps.LatLngBounds();

                        // Iterate through each location in the locationsData array
                        locationsData.forEach(function(location) {
                            // Define the center of the circle marker
                            var circleCenter = { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) };

                            // Define content for the circle marker popup
                            var circleContentString = '<div><strong>Client Name:</strong> ' + location.client_name + '</div>';

                            // Add circle marker with a radius of 50 meters
                            var circle = new google.maps.Circle({
                                strokeColor: '#3ff838', // Red border color
                                strokeOpacity: 0.2,
                                strokeWeight: 2,
                                fillColor: '#3ff838', // Red fill color
                                fillOpacity: 0.2,
                                map: map,
                                center: circleCenter,
                                radius: 100 // Radius in meters
                            });

                            // Add click event listener to display InfoWindow when clicked on the circle marker
                            circle.addListener('click', function(e) {
                                var infoWindow = new google.maps.InfoWindow({
                                    content: circleContentString,
                                    position: circleCenter // Display InfoWindow at the center of the circle marker
                                });
                                infoWindow.open(map);
                            });

                            // Extend the bounds to include the circle marker's position
                            bounds.extend(circleCenter);
                        });

                        // Fit the map to the bounds to ensure all circle markers are visible
                        map.fitBounds(bounds);
                    } else {
                        console.error('No location data available.'); // Log an error if locationsData is empty
                    }





                    // Add markers for interval coordinates with a specific color
                    addMarkers(intervalData, {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 3, // Adjust the size of the dot
                        fillColor: '#0ab705', // Green color for interval markers
                        fillOpacity: 1, // Opacity of the dot
                        strokeWeight: 0 // No border
                    }, false);

                    // Add markers for call coordinates with a pin icon
                    addMarkers(callsData, {
                        url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png', // URL of the pin icon
                        scaledSize: new google.maps.Size(44, 44) // Adjust the size of the pin icon
                    }, true);



                    @if($start)
                    // Add a yellow "start" icon
                    var startMarker = new google.maps.Marker({
                        position: { lat: {!! $start->latitude !!}, lng: {!! $start->longitude !!} }, // Position of the start marker based on the $start variable
                        map: map,
                        icon: {
                            url: 'https://maps.google.com/mapfiles/markerS.png', // Custom marker with the letter 'S'
                            scaledSize: new google.maps.Size(30, 30), // Adjust the size of the icon
                        }
                    });
                    @endif



                    // Create a polyline to display the route
                    var routePolyline = new google.maps.Polyline({
                        path: pathCoordinates,
                        geodesic: true,
                        strokeColor: '#04047e',
                        strokeOpacity: 0.5,
                        strokeWeight: 0.2
                    });

                    // Set the polyline on the map
                    routePolyline.setMap(map);

                    // If pathCoordinates is empty, set the default location as the center of the map
                    if (pathCoordinates.length === 0) {
                        map.setCenter(defaultLocation);
                    } else {
                        // Calculate the center point of the route
                        var bounds = new google.maps.LatLngBounds();
                        pathCoordinates.forEach(function(coord) {
                            bounds.extend(coord);
                        });
                        map.fitBounds(bounds);
                    }

                    var fakeGpsStatus = document.getElementById('fakeGpsStatus');
                    var isFakeGpsDetected = detectFakeGPS(previousLocation, currentLocation);
                    fakeGpsStatus.textContent = isFakeGpsDetected ? 'Detected' : 'Not Detected';
                }

                function centerMapOnPin(map, latitude, longitude) {
                    // Convert latitude and longitude to numbers
                    var latLng = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
                    // Set the center of the map to the clicked pin
                    map.setCenter(latLng);

                }
                function detectFakeGPS(previousLocation, currentLocation) {
                    // Check for sudden jumps in location
                    if (previousLocation && currentLocation) {
                        var distance = calculateDistance(previousLocation, currentLocation);
                        var timeDiff = calculateTimeDifference(previousLocation.timestamp, currentLocation.timestamp);
                        var speed = distance / timeDiff; // Calculate speed based on distance and time difference
                        if (speed > MAX_SPEED_THRESHOLD) {
                            return true; // Sudden jump in location, potential fake GPS
                        }
                    }

                    // Check location accuracy
                    if (currentLocation.accuracy > MAX_ACCURACY_THRESHOLD) {
                        return true; // Location accuracy exceeds threshold, potential fake GPS
                    }

                    // Additional checks based on device information, mock location detection, etc.
                    // Add your implementation here

                    return false; // No indications of fake GPS
                }

                function calculateDistance(location1, location2) {
                    // Calculate distance between two GPS coordinates (Haversine formula)
                    var earthRadius = 6371000; // Earth's radius in meters
                    var lat1 = toRadians(location1.latitude);
                    var lon1 = toRadians(location1.longitude);
                    var lat2 = toRadians(location2.latitude);
                    var lon2 = toRadians(location2.longitude);
                    var deltaLat = lat2 - lat1;
                    var deltaLon = lon2 - lon1;

                    var a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                        Math.cos(lat1) * Math.cos(lat2) *
                        Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                    return earthRadius * c;
                }

                function calculateTimeDifference(previousTimestamp, currentTimestamp) {
                    // Calculate time difference in seconds
                    return (currentTimestamp - previousTimestamp) / 1000; // Convert milliseconds to seconds
                }

                function toRadians(degrees) {
                    return degrees * Math.PI / 180;
                }

            </script>
            <style>
                /* Style for the visit timeline section */
                .content__table.visit_time_line {
                    margin-bottom: 20px; /* Adjust as needed */
                }

                /* Style for the table */
                .table-responsive {
                    overflow-x: auto;
                }

                /* Style for the map section */
                .map_section {
                    margin-top: 20px; /* Adjust as needed */
                }

                /* Style for the map container */
                #map {
                    height: 400px; /* Adjust the height as needed */
                    width: 100%;
                    border: 1px solid #ccc; /* Add border for better visualization */
                    border-radius: 5px; /* Add border radius for better visualization */
                }

                /* Style for the buttons and controls */
                .visit_map_actions_wrapper {
                    margin-bottom: 20px; /* Adjust as needed */
                }

                .visit_map_actions_wrapper .visit_map_action_wrapper {
                    display: inline-block;
                    margin-right: 10px; /* Adjust as needed */
                }

                /* Style for the map key button */
                .visit_map_key_wrapper button {
                    background-color: #f0f0f0; /* Change background color as needed */
                    color: #333; /* Change text color as needed */
                    border: 1px solid #ccc; /* Add border for better visualization */
                    border-radius: 5px; /* Add border radius for better visualization */
                }

                /* Style for the hidden section on smaller screens */
                #map-left-section {
                    display: none;
                }

                /* Style for the loading animation */
                #loading-visit-list {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100px; /* Adjust as needed */
                }
                .daily_activity_reports.index #map-left-section {
                    float: left;
                    width: 326px;
                    height: 60vh;
                    word-wrap: break-word;
                    white-space: normal;
                    box-sizing: border-box;
                    overflow: auto;
                    margin-bottom: 0;
                    padding: 0 8px;
                    background: #FAFAFA;
                    border-right: 1px solid #eee;
                    scroll-behavior: smooth;
                }

                /* Style for the visit map */
                .visit_map {
                    position: relative;
                    overflow: hidden;
                }
                .custom-card {
                    border: 1px solid rgba(0, 0, 0, 0.125);
                    border-radius: 0.25rem;
                }
                .green-circle {
                    border: 2px solid green;
                    color: green;
                    border-radius: 50%;
                    width: 25px;
                    height: 25px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>
@endsection
@section('chart-scripts')
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
@endsection
