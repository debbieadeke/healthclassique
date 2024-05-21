<div id="sidebar-menu" class="sidebar-menu">
    <ul>
        <li class="menu-title">Main</li>
        <li>
            <a href="{{route('home')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-01.svg') }}" alt=""></span> <span> Dashboard </span></a>
        </li>
        <li>
            <a href="/messages"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/draft.svg') }}" alt=""></span> <span> Messages </span>
                <div class="chat-user-count">
                    @if ($unReadMessagesCount > 0)
                        <span class="nmbr">{{ $unReadMessagesCount }}</span>
                    @endif
                </div>
            </a>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/call-incoming.svg') }}" alt=""></span> <span> Calls </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li>
                    <div class="btn-group">
                        <a type="submit" class="me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Doctor Calls</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('salescalls.create-doctor')}}">New Call</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{route('salescalls.list-doctor')}}">View Calls To-Date</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <div class="btn-group">
                        <a type="submit" class="me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Clinic Calls</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('salescalls.create')}}">New Call</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{route('salescalls.list')}}">View Calls To-Date</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <div class="btn-group">
                        <a type="submit" class="me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Pharmacy Calls</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{route('salescalls.create-pharmacy')}}">New Call</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{route('salescalls.list-pharmacy')}}">View Calls To-Date</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{route('salescalls.new_pharmacy_clinic')}}"> New Pharmacy/Clinics</a>
                </li>
                <li>
                    <a href="{{route('salescalls.new_doctor')}}"> New Doctors</a>
                </li>
                <li>
                    <a href="{{route('salescalls.general_uploads')}}"> General Uploads</a>
                </li>
                <li>
                    <a href="{{route('salescalls.pob_uploads')}}"> POB Uploads</a>
                </li>
                <li>
                    <a href="{{route('salescalls.record-sale')}}">Record Sales</a>
                </li>
                <li>
                    <a href="{{route('gps.index')}}"> Client GPS Location</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{route('salescalls.view-prescription-audits')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-15.svg') }}" alt=""></span> <span>PxN Audits</span> <div class="chat-user-count">
                    {{--                    <span class="nmbr">{{$pxnAuditsCount}}</span>--}}
                </div>
            </a>
        </li>
        <li>
            <a href="{{route('salescalls.view-orders-booked')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/chat-icon-06.svg') }}" alt=""></span> <span>POBs</span> <div class="chat-user-count">
                    {{--                    <span class="nmbr">{{$ordersBookedCount}}</span>--}}
                </div>
            </a>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/folder-icon-01.svg') }}" alt=""></span> <span> SAMPLES </span> <span class="menu-arrow"></span>
            </a>
            <ul style="display: none;">

                <li><a class="dropdown-item" href="{{route('sample-batch.issue-sample-request')}}">Issue Sample</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.report')}}">View Sample Report</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.sample-balance')}}">Sample Balance </a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.view-sample-slips')}}">View Sample Slips </a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-03.svg') }}" alt=""></span> <span> CUSTOMER MGMT </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{ route('client-users.index') }}">Manage Doctor</a></li>
                <li><a class="dropdown-item" href="{{ route('facility-users.index', ['facility_type' => 'Clinic'])}}">Manage Clinic</a></li>
                <li><a class="dropdown-item" href="{{ route('pharmacy-users.index', ['facility_type' => 'Pharmacy'])}}">Manage Pharmacies</a></li>
                <li><a class="dropdown-item" href="{{ route('locations.location.index') }}">Manage Locations</a></li>
                <li><a class="dropdown-item" href="{{ route('targets.customers') }}">Set Clinic Targets</a></li>
                <li><a class="dropdown-item" href="{{ route('targets.pharmacy') }}">Set Pharmacy Targets</a></li>
                <li><a class="dropdown-item" href="{{ route('targets.sales_rep_target') }}">view Targets</a></li>

            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-08.svg') }}" alt=""></span> <span> HR Section </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('leaves.user_index')}}">Leave Application</a></li>
            </ul>
        </li>
    </ul>
    <div class="logout-btn">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/logout.svg') }}" alt=""></span> <span>Logout</span></a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if geolocation is supported by the browser
        if ("geolocation" in navigator) {
            // Ask the user for permission to access their location
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            // Geolocation is not supported by the browser
            alert('Geolocation is not supported by this browser. Please use a different browser or enable location services.');
        }
    });

    function successCallback(position) {
        // Access user's location here
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // Proceed with the rest of your code here
        var now = new Date();
        var hours = now.getHours();

        // Check if the current time is between 7:00 AM and 6:00 PM
        if (hours >= 7 && hours < 18) {
            // Check if the GPS location has already been sent
            sendInitialGPSLocation(latitude, longitude);
        } else {
            console.log('GPS location can only be sent between 7:00 AM and 6:00 PM.');
        }

        // Function to continuously update and send GPS coordinates to the server
        function updateAndSendGPSLocation() {
            // Get the current time
            var now = new Date();
            var hours = now.getHours();

            // Check if the current time is between 7:00 AM and 6:00 PM
            if (hours >= 7 && hours < 18) {
                // Send the GPS coordinates to the server using AJAX
                sendIntervalGPSLocation(latitude, longitude);
            } else {
                console.log('GPS location can only be sent between 7:00 AM and 6:00 PM.');
            }
        }

        // Update and send GPS location every 30 seconds (adjust as needed)
        setInterval(updateAndSendGPSLocation, 30000);
    }

    // Error callback function
    var permissionPrompted = false; // Flag to track whether permission has been prompted

    // Error callback function
    function errorCallback(error) {
        // Handle errors here
        switch (error.code) {
            case error.PERMISSION_DENIED:
                // User denied the request for Geolocation
                if (!permissionPrompted) {
                    permissionPrompted = true; // Set the flag to true
                    alert('To use this app, you must allow access to your location.');
                }
                break;
            case error.POSITION_UNAVAILABLE:
                // Location information is unavailable
                alert('Location information is unavailable.');
                break;
            case error.TIMEOUT:
                // The request to get user location timed out
                alert('The request to get your location timed out. Please try again later.');
                break;
            case error.UNKNOWN_ERROR:
                // An unknown error occurred
                alert('An unknown error occurred while trying to access your location.');
                break;
        }
        var retry = confirm('Do you want to retry allowing GPS location?');
        if (retry) {
            // Retry getting the user's location
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        } else {
            // Optionally, reload the page or display alternative content
            location.reload(); // Reload the page
            // Alternatively, you can display alternative content or disable features that require location
        }

    }

    // Function to send initial GPS coordinates to the server using AJAX
    function sendInitialGPSLocation(latitude, longitude) {
        var token = '{{ csrf_token() }}'; // CSRF token for Laravel
        var url = '{{ route("gps.store-gps-location") }}'; // Route for initial GPS location

        // AJAX request to send GPS coordinates to the server
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: token,
                latitude: latitude,
                longitude: longitude
            },
            success: function(response) {
                console.log('Initial GPS location sent successfully.');
                // Set flag in local storage to indicate that GPS location has been sent
                localStorage.setItem('gpsLocationSent', true);
            },
            error: function(xhr, status, error) {
                console.error('Error sending initial GPS location:', error);
            }
        });
    }

    // Function to send interval GPS coordinates to the server using AJAX
    function sendIntervalGPSLocation(latitude, longitude) {
        var token = '{{ csrf_token() }}'; // CSRF token for Laravel
        var url = '{{ route("gps.interval-gps-location") }}'; // Route for interval GPS location

        // AJAX request to send GPS coordinates to the server
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                _token: token,
                latitude: latitude,
                longitude: longitude
            },
            success: function(response) {
                console.log('Interval GPS location sent successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error sending interval GPS location:', error);
            }
        });
    }
</script>


