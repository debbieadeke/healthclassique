@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Client GPS Location</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Client GPS Location</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="gpsForm" method="post" action="{{ route('gps.store-client-location') }}" enctype="multipart/form-data" onsubmit="return validateForm()" onchange="updateClientType()" required>
                            @csrf
                            <input type="hidden" name="client_type" id="clientType" value="">
                            <input type="hidden" name="client_name" id="clientName" value="">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="client_id" class="form-label"><b>Select Client</b></label>
                                        <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required>
                                            <option value="" selected>Select Client</option>
                                            @foreach($clients as $client)
                                                <option class="form-control" value="{{ $client->id }}" data-client-code="{{ $client->code }}" data-client-name="{{ $client->name }}" data-client-type="{{ $client->client_type }}">
                                                    {{ $client->name . '  (' . $client->client_type . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="latitude" class="form-label"><b>Latitude</b></label>
                                        <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="longitude" class="form-label"><b>Longitude</b></label>
                                        <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-4">
                                            <button type="button" class="btn btn-primary" onclick="getUserLocation()">Get GPS</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="mt-4">
                                            <button id="submitGpsButton" type="submit" class="btn btn-success" name="action" value="item_submit">Submit GPS</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Clients GPS Records</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Client Name</th>
                                <th>Type</th>
                                <th>latitude</th>
                                <th>longitude</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($locations as $location)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $location->client_name }}</td>
                                    <td>{{ $location->client_type }}</td>
                                    <td>{{ $location->latitude }}</td>
                                    <td>{{ $location->longitude }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var latitude = null;
        var longitude = null;

        function getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Get the latitude and longitude
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;

                    // Set the latitude and longitude values in the form fields
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;

                    // Enable the "Submit GPS" button
                    document.getElementById('submitGpsButton').disabled = false;
                }, function(error) {
                    console.error('Error getting user location:', error);
                    alert('Error getting user location. Please enable location services and try again.');
                });
            } else {
                console.error('Geolocation is not supported by this browser.');
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
    <script>
        function updateClientType() {
            // Get the selected option
            var selectedOption = document.getElementById('client_id').options[document.getElementById('client_id').selectedIndex];

            // Get the client type from the selected option's data attribute
            var clientType = selectedOption.dataset.clientType;
            var clientName = selectedOption.dataset.clientName;

            // Set the client type value to the hidden input field
            document.getElementById('clientType').value = clientType;
            document.getElementById('clientName').value = clientName;
        }
    </script>

@endsection
