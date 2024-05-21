@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>GPS Map</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">GPS Map</li>
                    </ol>
                </nav>
            </div>
        </div>
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
                                        // Initialize an array to hold the counts for each hour
                                        $callCounts = array_fill(7, 14, 0);

                                        // Count the calls made in each hour
                                        foreach ($calls as $call) {
                                            $recordedHour = date('H', strtotime($call->recorded_at));
                                            if ($recordedHour >= 7 && $recordedHour < 20) {
                                                $callCounts[$recordedHour]++;
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
                                                $count = $callCounts[$hour] ?? 0; // Ensure count is available for each hour
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
                                                $count = $callCounts[$hour];
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
                                            <div id="loading-visit-list" style="display: none;">
                                                <img alt="loading" class="loading" src="/assets/gif-load-f5f0a53e4d09ba86216c2378a6ec4d807fe7bc924f5460ca499c6e0ad60ce26b.gif">
                                            </div>
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
    </div>
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

        // Add markers for interval coordinates with a specific color
        addMarkers(intervalData, {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 4, // Adjust the size of the dot
            fillColor: '#0ab705', // Green color for interval markers
            fillOpacity: 1, // Opacity of the dot
            strokeWeight: 0 // No border
        }, false);

        // Add markers for call coordinates with a pin icon
        addMarkers(callsData, {
            url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png', // URL of the pin icon
            scaledSize: new google.maps.Size(44, 44) // Adjust the size of the pin icon
        }, true);

        // Create a polyline to display the route
        var routePolyline = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: '#04047e',
            strokeOpacity: 1.0,
            strokeWeight: 1
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
