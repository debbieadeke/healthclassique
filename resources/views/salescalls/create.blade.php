@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Sales Calls</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Create a Clinic Call</li>
                        </ol>
                    </nav>
                    <button type="button" class="btn btn-primary btn-sm py-1 mt-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        View Report
                    </button>
                </div>
                <div class="col-4">
                    <div id="radial_1" style="min-height: 150px;">
                        <div id="apexcharts-container" style="width: 200px; height: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="hospitalForm" action="{{ route('salescalls.store-hospital') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            <div class="form-body">

                                <!-- Select Clinic -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-8">
                                            <label for="client_id" class="form-label"><b>Select Clinic</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getFacilityDetails();updateChart();updateTable();updateDoctors();">
                                                @if ($pagetitle == "Add New Sales Call (Clinic)")
                                                    <option value="" selected>Select Clinic</option>
                                                    {{--                                                    <option value="add_new">Add New Clinic</option>--}}
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
                                    <div class="col-md-4">
                                        <label for="myclass" class="form-label"><b>Class</b></label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="myclass" class="form-label"><b>Total RMO's</b></label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                    </div>


                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title fs-5" id="exampleModalLabel"><b id="clinicName"></b></h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4><b>{{ date('F') }}</b> Sales Items Report</h4>
                                                    <div class="table-responsive">
                                                        <table class="table mb-0 border-0  custom-table table-dash" id="salesTable">
                                                            <thead>
                                                            <tr>
                                                                <th>Product <br> Code</th>
                                                                <th>Product Name</th>
                                                                <th>Target</th>
                                                                <th>Achieved</th>
                                                                <th>(%)</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="row pt-4">
                                                        <h4><b>Doctors Coverage </b></h4>
                                                        <div class="row pt-3">
                                                            <div class="col-md-6">
                                                                <div class="row mb-3">
                                                                    <label for="rmosCount" class="col-sm-7 col-form-label"><h5><b>Number RMO's</b></h5></label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" class="form-control" id="rmosCount" name="rmosCount" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row mb-3">
                                                                    <label for="callsRmo" class="col-sm-7 col-form-label"><h5><b>Calls TD RMO's</b></h5></label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" class="form-control" id="callsRmo" name="callsRmo" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table-dash p-2">
                                                            <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1" id="doctorsTable">
                                                                <thead>
                                                                <tr>
                                                                    <th>Client Name</th>
                                                                    <th>Category</th>
                                                                    <th>Speciality</th>
                                                                    <th>Last Visit</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-4" id="AddFacilityRowDiv" style="display:none; flex-direction: row; background-color: #f8f9fa">
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

                                <div class="row pt-3">
                                    <div class="col-md-3">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="title_id" class="form-label"><b>Title</b></label>
                                            <select class="form-control" id="title_id" name="title_id">
                                                @foreach($titles as $title)
                                                    <option class="form-control" value="{{$title->id}}">{{$title->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>First Name</b></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" aria-describedby="FirstNameHelp" autocomplete="on" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label"><b>Last Name</b></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" aria-describedby="LastNameHelp" autocomplete="on" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="speciality_id" class="form-label"><b>Speciality</b></label>
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
                              <div class="col-md-12">
                                  <!-- Discussion Summary -->
                                  <div class="mb-3">
                                      <label for="discussion_summary" class="form-label"><b>Doctors Feedback</b></label>
                                      <textarea class="form-control" id="discussion_summary" name="discussion_summary" rows="4" required></textarea>
                                  </div>
                              </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discussion_summary" class="form-label"><b>Key Products Current Stock Holding</b></label>
                                        <div class="scrollable-container" style="height: 300px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="product">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>Epimol-E Soap</span>
                                                                    <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="product">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>Epimol-B Baby 400ml</span>
                                                                    <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="product">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>Epimol-B AP 400ml</span>
                                                                    <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="product">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>Epimol-B Enterobasilia</span>
                                                                    <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="product">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span>Epimol-B Synolyn Drops</span>
                                                                    <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="product">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>Epimol-B Synolyn Aspirator</span>
                                                            <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                        </div>
                                                    </div>
                                                    <div class="product">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>Epimol-B HealthyFlow Inhalers</span>
                                                            <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                        </div>
                                                    </div>
                                                    <div class="product">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span>Epimol-B Klassik BP Machine</span>
                                                            <input type="number" class="form-control quantity-input" style="-moz-appearance: textfield;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>


                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="row g-1">
                                        <div class="col-8">
                                            <label for="first_name" class="form-label"><b>Sample Given</b></label>
                                        </div>
                                        <div class="col-4">
                                            <label for="last_name" class="form-label"><b>Quantity</b></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row g-1">
                                        <div class="col-8">
                                            <label for="first_name" class="form-label"><b>Sample Given</b></label>
                                        </div>
                                        <div class="col-4">
                                            <label for="last_name" class="form-label"><b>Quantity</b></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="col-6">
                                        <div class="row g-1">
                                            <div class="col-8">
                                                <div class="mb-3">
                                                    <select class="form-control select2"  id="product_id" name="product_id[]" onchange="processSamplesGiven()">
                                                        <option value="" selected>Select Sample</option>
                                                        @foreach($products as $product)
                                                            <option class="form-control" value="{{$product->product_id}}">{{$product->product->name}} (Qty: {{$product->quantity}})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="mb">
                                                    <select class="form-control" id="quantity" name="quantity[]" required>
                                                        <option value="0" selected>0</option>
                                                        <option value="1">1</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="row">
                                <div class="mb-3" id="SampleGivenYes" style="display:none">
                                    <div class="col-12">
                                        <label for="formFile" class="form-label"><b>Upload Sample Slip Image</b></label>
                                        <input class="form-control" type="file" id="UploadSampleSlip" name="UploadSampleSlip">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="order_booked" class="form-label"><b>Order Booked?</b></label>
                                        <select class="form-control" id="order_booked" name="order_booked" required onchange="processOrderBooking()">
                                            <option value="" selected>Select Response</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3" id="OrderBookNo" style="display:none">
                                        <label for="discussion_summary" class="form-label"><b>Reasons for not booking order</b></label>
                                        <textarea class="form-control" id="ReasonsForNotBooking" name="ReasonsForNotBooking" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3" id="OrderBookYes" style="display:none">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="formFile" class="form-label"><b>Upload Order Image</b></label>
                                                <input class="form-control" type="file" id="UploadOrder" name="UploadOrder">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <hr size=1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Next Planned Visit -->
                                    <div class="mb-3">
                                        <label for="next_planned_visit" class="form-label"><b>Next Planned Visit to Facility</b></label>
                                        <div class="input-group">
                                            <input type="datetime-local" id="next_planned_visit" name="next_planned_visit" class="form-control">
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
                                    <button type="submit" id="saveAnotherBtn" class="btn btn-primary" name="action" value="continue_hospital_submit" onclick="disableButton()">Save & Add another doctor</button>
                                    <button type="submit" id="finalizeBtn" class="btn btn-danger" name="action" value="store_hospital_submit" onclick="disableButton()">Submit & End Call</button>
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
    <style>

        html, body {
            height: 100%;
            margin: 0;
        }
        .apexcharts-datalabel-label  {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: blue;
        }
        @media (max-width:768px) {
            .form-label {
                font-size: 12px;
            }.form-control,.select2-container .select2-selection--single {
                 padding: 8px 15px !important;
                 min-height: auto;
                 border-radius: 7px!important;
                 font-size: 10px;
                 font-weight: normal;
                 line-height: normal;
             }
            .select2-container .select2-selection--single {
                /* border: 2px solid rgba(46, 55, 164, 0.1); */
                border-radius: 10px;
                height: 35px;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #676767;
                font-size: 11px;
                font-weight: normal;
                line-height: normal;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                padding-right: 0px;
                padding-left: 1px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 29px;
                right: 7px;
            }
            .doctor-content h4 {
                font-size: 16px;
                color: #37429b;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                font-weight: 600;}
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type="number"] {
            height: 20px;
            width: 40px;
            padding: 1px;
            margin: 2px;
        }


    </style>
    <script>
        // Function to enable buttons
        function enableButtons() {
            document.getElementById('saveAnotherBtn').disabled = false;
            document.getElementById('finalizeBtn').disabled = false;
        }

        // Function to disable button upon click and enable after 5 seconds
        function disableButton() {
            // Disable the button after a short delay to allow form submission
            setTimeout(function() {
                // Disable buttons upon click
                document.getElementById('saveAnotherBtn').disabled = true;
                document.getElementById('finalizeBtn').disabled = true;

                // Enable buttons after a delay of 5 seconds
                setTimeout(enableButtons, 7000); // 5000 milliseconds = 5 seconds
            }, 100);
        }
    </script>
    <script>
        function enableButtons() {
            document.getElementById('saveAnotherBtn').disabled = false;
            document.getElementById('finalizeBtn').disabled = false;
        }

        function validateForm() {
            var selectOption2 = document.getElementById("order_booked");
            var fileUpload2 = document.getElementById("UploadOrder");
            var reasons2 = document.getElementById("ReasonsForNotBooking");

            var longitude = document.getElementById("longitude");

            var location_check_setting = document.getElementById("location_check_setting");


            if (longitude.value === "" && location_check_setting === "On") {
                alert("Critical Error: Please Logout from the system then enable location capture in your tablet then retry");
                enableButtons(); // Enable the buttons if validation fails
                return false;
            }


            if (selectOption2.value === "Yes" && fileUpload2.files.length === 0) {
                alert("You must upload an Order Booking");
                enableButtons(); // Enable the buttons if validation fails
                return false;
            }

            if (selectOption2.value === "No" && reasons2.value === "") {
                alert("You must provide a reason why you didn't Book an Order");
                enableButtons(); // Enable the buttons if validation fails
                return false;
            }


            var sampleSelect1 = document.getElementById("product_id");
            var sampleSelect2 = document.getElementById("product_id2");
            var sampleSelect3 = document.getElementById("product_id3");

            if ((sampleSelect1.value !== "") || (sampleSelect2.value !== "") || (sampleSelect3.value !== "")) {
                if (UploadSampleSlip.files.length === 0) {
                    alert("You must upload a Sample Slip");
                    enableButtons(); // Enable the buttons if validation fails
                    return false;
                }
            }



            document.getElementById('hospitalForm').addEventListener('submit', function(event) {
                console.log('Form submission event triggered');

                // Check if any required fields are empty
                var form = event.target;
                if (!form.checkValidity()) {
                    console.log('Form submission failed due to empty required fields');
                    enableButtons(); // Enable buttons if form submission fails
                }
            });

            return true; // Disable the button if validation succeeds
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var productMetrics = @json($productMetrics);

            // Initial chart setup
            var initialOptions = {
                series: [0],
                chart: {
                    type: 'radialBar',
                    height: 200,
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: '60%',
                        },
                        track: {
                            strokeWidth: '70%', // Adjust thickness here
                        },
                    },
                },
                fill: {
                    colors: ['#04047e'],
                },
                labels: ['Performance'],
            };

            var chart = new ApexCharts(document.querySelector("#apexcharts-container"), initialOptions);
            chart.render();

            // Function to update the chart based on selected facility
            window.updateChart = function () {
                var selectedFacilityId = document.getElementById("client_id").value;
                var selectedFacilityCode = document.querySelector(`option[value="${selectedFacilityId}"]`).dataset.clientCode;

                // Assuming $productsByFacility is passed from your Laravel controller
                var productsByCustomer = @json($productMetrics);

                // Retrieve product metrics for the selected customer code
                var productMetrics = productsByCustomer[selectedFacilityCode] || [];

                var achievedValue = 0;
                var targetValue = 0;

                productMetrics.forEach(function (productMetric) {
                    targetValue += parseFloat(productMetric['target_value']);
                    achievedValue += parseFloat(productMetric['achieved_value']);
                });

                var overallPercentage = (achievedValue / targetValue) * 100;
                var formattedPerformance = overallPercentage.toFixed(0);
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
        function updateTable() {
            console.log('updateTable called');
            var selectedFacilityId = document.getElementById("client_id").value;
            var selectedFacilityCode = document.querySelector(`option[value="${selectedFacilityId}"]`).dataset.clientCode;

            // Assuming $productsByFacility is passed from your Laravel controller
            var productsByCustomer = @json($productMetrics);


            // Retrieve product metrics for the selected customer code
            var productMetrics = productsByCustomer[selectedFacilityCode] || [];
            console.log(productMetrics);
            // Update the table content
            var tableBody = document.getElementById("salesTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = "";

            productMetrics.forEach(function (productMetric) {
                var row = tableBody.insertRow();
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                var cell5 = row.insertCell(4);
                var cell6 = row.insertCell(5);


                cell1.innerHTML = productMetric.product_code;
                cell2.innerHTML = productMetric.product;
                cell3.innerHTML = productMetric.target;
                cell4.innerHTML = productMetric.quantity;


                var performance = (productMetric.achieved_value / productMetric.target_value) * 100 || 0;
                var formattedPerformance = performance.toFixed(0);
                cell5.innerHTML = '<span class="text-success">' + formattedPerformance + '%</span>';
            });

            // Set the clinic name dynamically
            var clinicNameElement = document.getElementById("clinicName");
            clinicNameElement.innerHTML = productMetrics.length > 0 ? productMetrics[0].customer_name : "Unknown";

            var clinicRmoElement = document.getElementById("rmosCount");
            clinicRmoElement.value = productMetrics.length > 0 ? productMetrics[0].customer_rmos : 0;

            var callsRmoElement = document.getElementById("callsRmo");
            callsRmoElement.value = productMetrics.length > 0 ? productMetrics[0].customer_call_rmos : 0;
        }
    </script>
    <script>
        function updateDoctors() {
            console.log('updateDoctors called');
            var selectedFacilityId = document.getElementById("client_id").value;

            // Pass Doctors Data
            var doctors = @json($doctors);

            // Retrieve doctors seen for the selected facility
            var doctorsSeen = doctors[selectedFacilityId] || [];

            // Update the table content
            var tableBody = document.getElementById("doctorsTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = "";

            doctorsSeen.forEach(function(doctor) {
                var row = tableBody.insertRow();
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);


                cell1.innerHTML =doctor.first_name + " " + doctor.last_name;
                cell2.innerHTML = doctor.category;
                cell3.innerHTML =doctor.specialities.name;


                if (doctor.last_call) {
                    var lastCallDate = new Date(doctor.last_call);
                    var currentDate = new Date();
                    var daysPassed = Math.floor((currentDate - lastCallDate) / (1000 * 60 * 60 * 24));

                    // Apply different styles based on the number of days passed
                    if (daysPassed > 40) {
                        cell4.innerHTML = "<span style='color: red;'>" + daysPassed + " days ago</span>";
                    } else {
                        cell4.innerHTML = "<span style='color: green;'>" + daysPassed + " days ago</span>";
                    }
                } else {
                    cell4.innerHTML = "Not visited";
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection
