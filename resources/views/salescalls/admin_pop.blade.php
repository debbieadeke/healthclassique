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
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('salescalls.store-hospital') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            <div class="form-body">

                                <!-- Select Clinic -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label">Select Clinic</label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getFacilityDetails();updateChart();updateTable();">
                                                @if ($pagetitle == "Add New Sales Call (Clinic)")
                                                    <option value="" selected>Select Clinic</option>
                                                    <option value="add_new">Add New Clinic</option>
                                                @endif
                                                @foreach($clients2 as $client)
                                                        <?php
                                                        $visited_today = in_array($client->id, $sales_call_ids);
                                                        $in_appointment = in_array($client->id, $appointments_ids);
                                                        ?>
                                                    @if ($client->pivot->class != null)
                                                        <option class="form-control" value="{{$client->id}}" data-client-code="{{$client->code}}" data-extra-class="{{$client->pivot->class}}">{{$client->name}}
                                                            @if ($visited_today)
                                                                (Visited today)
                                                            @endif
                                                            @if ($in_appointment)
                                                                (In Appointment)
                                                            @endif
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="myclass" class="form-label">Class</label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <div>
                                            @if(config('settings.sales_upload_module_toggle') === 'On')
                                                <div id="radial_1" style="min-height: 150px;">
                                                    <div id="apexcharts-container"></div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    @if(config('settings.sales_upload_module_toggle') === 'On')
                                        <div class="col-md-2" style="padding-top:30px">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                View Report
                                            </button>
                                        </div>
                                    @endif

                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">My Sales Report</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Report Details</h5>
                                                    <div class="table-responsive">
                                                        <table class="table mb-0 border-0  custom-table table-stripe" id="salesTable">
                                                            <thead>
                                                            <tr>
                                                                <th>Product <br> Code</th>
                                                                <th>Product <br>Name</th>
                                                                <th>Target</th>
                                                                <th>Achievement</th>
                                                                <th>Performance <br> (%)</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row" id="AddFacilityRowDiv" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                    <div class="container p-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="newfirstname" class="form-label">Clinic Name</label>
                                                <input type="text" class="form-control" id="newfacilityname" name="newfacilityname">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="code" class="form-label">Clinic Code</label>
                                                <input type="text" class="form-control" id="code" name="code">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="newclass" class="form-label">Class</label>
                                                <select class="form-control" id="newclass" name="newclass">
                                                    <option class="form-control" value="A" selected>A</option>
                                                    <option class="form-control" value="B">B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="newlocation" class="form-label">Location</label>
                                                <select class="form-control" id="newlocation" name="newlocation">
                                                    <option value="" selected>Select</option>
                                                    @foreach($newlocations as $newlocation)
                                                        <option class="form-control" value="{{$newlocation->id}}">{{$newlocation->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="title_id" class="form-label">Title</label>
                                            <select class="form-control" id="title_id" name="title_id">
                                                @foreach($titles as $title)
                                                    <option class="form-control" value="{{$title->id}}">{{$title->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" aria-describedby="FirstNameHelp" autocomplete="on">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" aria-describedby="LastNameHelp" autocomplete="on">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="speciality_id" class="form-label">Speciality</label>
                                            <select class="form-control" id="speciality_id" name="speciality_id" required onchange="getUserLocation()">
                                                <option value="" selected>Select Speciality</option>
                                                @foreach($specialities as $speciality)
                                                    <option class="form-control" value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Speciality (Drop-down) -->



                            <div class="row">
                                <!-- Discussion Summary -->
                                <div class="mb-3">
                                    <label for="discussion_summary" class="form-label">Discussion Summary</label>
                                    <textarea class="form-control" id="discussion_summary" name="discussion_summary" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Samples Given</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Quantity</label>
                                    </div>
                                </div>
                            </div>

                            @for($i=1; $i<=3; $i++)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <select class="form-control" id="product_id" name="product_id[]" onchange="processSamplesGiven()">
                                                <option value="" selected>Select Sample</option>
                                                @foreach($products as $product)
                                                    <option class="form-control" value="{{$product->id}}">{{$product->product->name}} (Qty: {{$product->quantity_remaining}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <select class="form-control" id="quantity" name="quantity[]" required>
                                                <option value="0" selected>0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                            <div class="row">
                                <div class="mb-3" id="SampleGivenYes" style="display:none">
                                    <div class="col-12">
                                        <label for="formFile" class="form-label">Upload Sample Slip Image</label>
                                        <input class="form-control" type="file" id="UploadSampleSlip" name="UploadSampleSlip">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Next Planned Visit -->
                                    <div class="mb-3">
                                        <label for="next_planned_visit" class="form-label">Next Planned Visit to Facility</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" value="" name="next_planned_visit">
                                            <input type="time" class="form-control" value="08:30:00" name="next_planned_time">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">

                                </div>
                            </div>




                            <div class="form-actions">
                                <div class="text-end">
                                    <input type="hidden" name="client_type" value="Clinic">
                                    <input type="hidden" name="longitude" id="longitude" value="">
                                    <input type="hidden" name="latitude" id="latitude" value="">
                                    @if ($pagetitle == "Add New Sales Call (Clinic)")
                                        <input type="hidden" name="start_time" value="{{$start_time}}">
                                    @else
                                        <input type="hidden" name="salescall_id" value="{{$salescall_id}}">
                                    @endif

                                    <button type="submit" class="btn btn-primary" name="action" value="continue_hospital_submit">Save & Add another doctor</button>
                                    <button type="submit" class="btn btn-danger" name="action" value="store_hospital_submit">Submit & End Call</button>
                                    <button type="reset" class="btn btn-light-danger text-danger font-medium">
                                        Reset
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function validateForm() {
            var longitude = document.getElementById("longitude");

            if (longitude.value === "") {
                alert("Critical Error: Please Logout from the system then enable location capture in your tablet then retry");
                return false;
            }

            return true;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Assuming $productMetrics is passed from your Laravel controller
            var productMetrics = @json($productMetrics);

            // Initial chart setup
            var initialOptions = {
                series: [0],
                chart: {
                    type: 'radialBar',
                    height: 150.7,
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '70%',
                        },
                    },
                },
                fill: {
                    colors: ['#37429b'],
                },
                labels: ['Performance'],
            };

            var chart = new ApexCharts(document.querySelector("#apexcharts-container"), initialOptions);
            chart.render();

            // Function to update the chart based on selected facility
            window.updateChart = function () {
                var selectedFacilityId = document.getElementById("client_id").value;
                var selectedFacilityCode = document.querySelector(`option[value="${selectedFacilityId}"]`).dataset.clientCode;

                // Filter productMetrics based on the selected facility code
                var filteredProductMetrics = productMetrics.filter(item => item.customer_code === selectedFacilityCode);

                var totalQuantity = 0;
                var totalTarget = 0;

                filteredProductMetrics.forEach(function (productMetric) {
                    totalQuantity += parseFloat(productMetric['quantity']);
                    totalTarget += parseFloat(productMetric['target']);
                });
                console.log(totalQuantity);

                var overallPercentage = (totalQuantity / totalTarget) * 100;

                var formattedPerformance = overallPercentage.toFixed(2);
                formattedPerformance = isNaN(formattedPerformance) ? 0 : formattedPerformance;

                // Update chart series and labels with formatted performance data
                chart.updateSeries([formattedPerformance]);
                chart.updateOptions({
                    labels: ['Performance'],
                });
            };
        });
    </script>
    <script>
        // Function to update the table based on selected facility
        function updateTable() {
            console.log('updateTable called');
            var selectedFacilityId = document.getElementById("client_id").value;
            var selectedFacilityCode = document.querySelector(`option[value="${selectedFacilityId}"]`).dataset.clientCode;

            var productMetrics = @json($productMetrics);
            var filteredProductMetrics = productMetrics.filter(item => item.customer_code === selectedFacilityCode);

            // Update the table content
            var tableBody = document.getElementById("salesTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = "";

            filteredProductMetrics.forEach(function (productMetric) {
                var row = tableBody.insertRow();
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                var cell5 = row.insertCell(4);

                cell1.innerHTML = productMetric.product_code;
                cell2.innerHTML = productMetric.product;
                cell3.innerHTML = productMetric.target;
                cell4.innerHTML = productMetric.quantity;


                var performance = (productMetric.sum_quantity / productMetric.target) * 100;
                var formattedPerformance = performance.toFixed(2);
                cell5.innerHTML = '<span class="text-success">' + formattedPerformance + '%</span>';
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection
