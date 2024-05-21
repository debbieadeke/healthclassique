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
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Create a Pharmacy Call</li>
                        </ol>
                    </nav>
                    <div class="filter-section d-flex">
                        <div class="mb-3 me-3">
                            <select class="form-select" id="month" name="month">
                                @foreach($months as $key => $month)
                                    <option name="selected_month" value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm py-1 mt-1" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        View Report
                    </button>
                </div>
                <div class="col-4">
                    <div id="radial_1" style="min-height: 150px;">
                        <div id="apexcharts-container"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('salescalls.store-pharmacy') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
							@csrf
							<div class="form-body">
                                <!-- Select Pharmacy -->
                                <div class="row g-2 g-md-3">
                                    <div class="col-md-6">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label"><b>Select Pharmacy</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="updateChart();updateTable();displayAddNewRow()">
                                            @if ($pagetitle == "Add New Sales Call (Pharmacy)")
                                                    <option value="" selected>Select Pharmacy</option>
{{--                                                    <option value="add_new">Add New Pharmacy</option>--}}
                                                @endif


                                            @foreach($clients2 as $client)
                                                <?php
                                                    $visited_today = in_array($client->id, $sales_call_ids);
                                                    $in_appointment = in_array($client->id, $appointments_ids);

                                                    // Find matching item in the parent data based on "customer_code" and "code"
                                                    $matchingItem = collect($productMetrics)->where('customer_code', $client->code)->first();
                                                ?>

                                                @if ($client->pivot->class != null)
                                                    <option class="form-control" value="{{$client->id}}" data-client-code="{{$client->code}}"  data-extra-class="{{$client->pivot->class}}">
                                                        {{$client->name}}
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
                                <div class="col-3">
                                    <div id="class_div" style="flex-direction: column;">
                                        <label for="myclass" class="form-label"><b>Class</b></label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                    </div>
                                </div>


                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"><b>{{ date('F') }}</b> Sales Items Report</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Report Details</h5>
                                                <div class="table-responsive">
                                                    <table class="table mb-0 border-0 custom-table table-dash" id="salesTable">
                                                        <thead>
                                                        <tr>
                                                            <th>Product <br> Code</th>
                                                            <th>Product <br>Name</th>
                                                            <th>Target</th>
                                                            <th>Achievement</th>
                                                            <th>(%)</th>
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
                                <div class="row" id="AddFacilityRow" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                    <div class="container p-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="newfirstname" class="form-label">Pharmacy Name</label>
                                                <input type="text" class="form-control" id="newfacilityname" name="newfacilityname">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="code" class="form-label">Pharmacy Code</label>
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

							<div id="pharmtechRowsContainer">
								<div id="rowContent">
								<div class="row pt-2 g-1 g-md-3">
								  <div class="col-3 col-md-2">
									<!-- Double Call (Drop-down) -->
                                      <div class="mb-3">
                                          <label for="title_id" class="form-label"><b>Title</b></label>
                                          <select class="form-control" id="title_id" name="title_id[]">
									  @foreach($titles as $title)
											<option class="form-control" value="{{$title->id}}">{{$title->name}}</option>
									  @endforeach
														</select>
													</div>
								  </div>
								  <div class="col-3">
									<div class="mb-3">
										<label for="first_name" class="form-label"><b>First Name</b></label>
										<input type="text" class="form-control" id="first_name" name="first_name[]" aria-describedby="FirstNameHelp" oninput="getUserLocation()" autocomplete="on" required>
									</div>
								  </div>
								  <div class="col-3">
									<div class="mb-3">
									  <label for="last_name" class="form-label"><b>Last Name</b></label>
										<input type="text" class="form-control" id="last_name" name="last_name[]" aria-describedby="LastNameHelp" autocomplete="on" required>
									</div>
								  </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="speciality_id" class="form-label"><b>Speciality</b></label>
                                            <select class="form-control" id="speciality_id" name="speciality_id[]" required onchange="getUserLocation()">
                                                <option value="" selected>Select Speciality</option>
                                                @foreach($specialities as $speciality)
                                                    <option class="form-control" value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
								  <div class="col-3 col-md-3">
									<div class="mb-3">
									  <label for="contacts" class="form-label"><b>Contacts</b></label>
                                        <input type="text" class="form-control" id="contacts" name="contact[]" aria-describedby="ContactsHelp" autocomplete="on" required>
									</div>
								  </div>
								</div>
								<div class="row">
									<!-- Discussion Summary -->
									<div class="mb-3">
										<label for="discussion_summary" class="form-label"><b>Discussion Summary</b></label>
										<textarea class="form-control" id="discussion_summary" name="discussion_summary[]" rows="4" required></textarea>
									</div>
								</div>
								</div>
							</div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <a href="#" id="addPharmtechRow"><b>Add New Pharmtech Row</b></a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label"><b>Samples Given</b></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label"><b>Quantity</b></label>
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
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
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

							<div class="row mb-1">
                                <div class="co12">
                                    <hr size=1>
                                </div>
                            </div>

							<div class="row g-2 g-md-3">
								<div class="col-6">
									<div class="mb-3">
										<label for="order_booked" class="form-label"><b>Order Booked?</b></label>
										<select class="form-control" id="order_booked" name="order_booked" required onchange="processOrderBooking()">
											  <option value="" selected>Select Response</option>
											  <option value="Yes">Yes</option>
											  <option value="No">No</option>
										</select>
									</div>
								</div>
								<div class="col-6">
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

							<div class="row g-2 g-md-3">
								<div class="col-6">
									<div class="mb-3">
										<label for="prescription_audited" class="form-label"><b>Prescription Audit Done?</b></label>
										<select class="form-control" id="prescription_audited" name="prescription_audited" required onchange="processPrescriptionAudit()">
											  <option value="" selected>Select Response</option>
											  <option value="Yes">Yes</option>
											  <option value="No">No</option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3" id="PrescriptionAuditNo" style="display:none">
										<label for="discussion_summary" class="form-label">Reasons for not auditing</label>
										<textarea class="form-control" id="ReasonsForNotAuditing" name="ReasonsForNotAuditing" rows="3"></textarea>
									</div>

									<div class="mb-3" id="PrescriptionAuditYes" style="display:none">
										<label for="formFile" class="form-label">Upload Prescription Image</label>
										<input class="form-control" type="file" id="UploadPrescription" name="UploadPrescription" >

                    <div class="row mt-2">

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
                                      <label for="next_planned_visit" class="form-label"><b>Next Planned Visit</b></label>
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
									<input type="hidden" name="client_type" value="Pharmacy">
                                      <input type="hidden" name="longitude" id="longitude" value="">
                                      <input type="hidden" name="latitude" id="latitude" value="">
                                      <input type="hidden" name="location_check_setting" id="location_check_setting" value="{{$location_check_setting}}">
                                      @if ($pagetitle == "Add New Sales Call (Pharmacy)")
                                        <input type="hidden" name="start_time" value="{{$start_time}}">
                                      @else
                                        <input type="hidden" name="salescall_id" value="{{$salescall_id}}">
                                      @endif
{{--                                       <button type="submit" class="btn btn-primary" name="action" value="continue_pharmacy_submit">Save & Add another doctor </button>--}}
                                      <button type="submit" id="finalizeBtn" class="btn btn-danger" name="action" onclick="disableButton()" value="store_pharmacy_submit">Submit & End Call</button>
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
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .apexcharts-datalabel-label  {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #04047e;
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
    </style>
    <script>
        // Function to enable buttons
        function enableButtons() {
            document.getElementById('finalizeBtn').disabled = false;
        }

        // Function to disable button upon click and enable after 5 seconds
        function disableButton() {
            // Disable the button after a short delay to allow form submission
            setTimeout(function() {
                document.getElementById('finalizeBtn').disabled = true;
                // Enable buttons after a delay of 5 seconds
                setTimeout(enableButtons, 7000); // 5000 milliseconds = 5 seconds
            }, 100);
        }
    </script>
    <script>
        function validateForm() {
            var selectOption = document.getElementById("prescription_audited");
            var fileUpload = document.getElementById("UploadPrescription");
            var reasons = document.getElementById("ReasonsForNotAuditing");

            var selectOption2 = document.getElementById("order_booked");
            var fileUpload2 = document.getElementById("UploadOrder");
            var reasons2 = document.getElementById("ReasonsForNotBooking");

            var longitude = document.getElementById("longitude");

            var location_check_setting = document.getElementById("location_check_setting");


            if (longitude.value === "" && location_check_setting === "On") {
                alert("Critical Error: Please Logout from the system then enable location capture in your tablet then retry");
                return false;
            }

            if (selectOption.value === "Yes" && fileUpload.files.length === 0) {
                alert("You must upload a Prescription Audit");
                return false;
            }

            if (selectOption.value === "No" && reasons.value === "") {
                alert("You must provide a reason why you didn't Audit");
                return false;
            }

            if (selectOption2.value === "Yes" && fileUpload2.files.length === 0) {
                alert("You must upload an Order Booking");
                return false;
            }

            if (selectOption2.value === "No" && reasons2.value === "") {
                alert("You must provide a reason why you didn't Book an Order");
                return false;
            }


            var sampleSelect1 = document.getElementById("product_id");
            var sampleSelect2 = document.getElementById("product_id2");
            var sampleSelect3 = document.getElementById("product_id3");

            if ((sampleSelect1.value !== "") || (sampleSelect2.value !== "") || (sampleSelect3.value !== "")) {
                if (UploadSampleSlip.files.length === 0) {
                    alert("You must upload a Sample Slip");
                    return false;
                }
            }

            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function openAnotherModal() {
            // Close the current modal
            $('#myModal').modal('hide');

            // Open another modal (replace 'anotherModal' with the ID of your second modal)
            $('#itemModal').modal('show');
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get the current month
            var currentMonth = new Date().getMonth() + 1; // JavaScript months are 0-based
            console.log("currentMonth:",currentMonth);

            var productMetrics = @json($productMetrics);

            console.log("productMetric:", productMetrics);

            // Initial chart setup
            var initialOptions = {
                series: [0],
                chart: {
                    type: 'radialBar',
                    height: 180,
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

                // Retrieve product metrics for the selected pharmacy code
                var productMetricsForFacility = productMetrics[selectedFacilityCode] || {};
                console.log("productMetricsForFacility:", productMetricsForFacility);


                // Retrieve product metrics for the current month
                var productMetricsForCurrentMonth = productMetricsForFacility[currentMonth] || {};
                console.log("productMetricsForCurrentMonth:", productMetricsForCurrentMonth);

                var achievedValue = 0;
                var targetValue = 0;

                // Iterate over the keys of the object
                Object.keys(productMetricsForCurrentMonth).forEach(function(key) {
                    var productMetric = productMetricsForCurrentMonth[key];
                    targetValue += parseFloat(productMetric['target_value']);
                    achievedValue += parseFloat(productMetric['achieved_value']);

                });
                console.log("targetValue:", targetValue);
                console.log("achievedValue:",achievedValue);

                var overallPercentage = (achievedValue / targetValue) * 100;
                var formattedPerformance = overallPercentage.toFixed(0);
                formattedPerformance = isNaN(formattedPerformance) ? 0 : formattedPerformance;

                console.log("overallPercentage:",overallPercentage);
                console.log("formattedPerformance:",formattedPerformance);

               // Update chart series and labels with formatted performance data
                chart.updateSeries([formattedPerformance]);
                chart.updateOptions({
                    labels: ['Performance'],
                });
            };

            // Call updateChart to initialize the chart with data for the current month and selected facility
            updateChart();

            // Select the month dropdown element
            var monthDropdown = document.getElementById("month");

            // Add event listener for change event
            monthDropdown.addEventListener("change", function() {

                // Get the selected month value
                currentMonth = parseInt(monthDropdown.value);

                console.log("New  currentMonth:", currentMonth);

                // Update the chart with data for the newly selected month
                updateChart();
                updateTable(currentMonth);
            });
        });
    </script>
    <script>
     function updateTable(currentMonth) {

         if (currentMonth === undefined) {
             currentMonth = new Date().getMonth() + 1;
         }
         //var currentMonth = new Date().getMonth() + 1;
         // Get the current month

            console.log('currentMonth for table:', currentMonth);
            console.log('updateTable called');
            var selectedFacilityId = document.getElementById("client_id").value;
            var selectedFacilityCode = document.querySelector(`option[value="${selectedFacilityId}"]`).dataset.clientCode;

            // Assuming $productsByFacility is passed from your Laravel controller
            var productsByCustomer = @json($productMetrics);

            // // Retrieve product metrics for the selected customer code
             var productMetrics = productsByCustomer[selectedFacilityCode] || [];

             console.log("productsByCustomer:", productMetrics);

             // Retrieve product metrics for the current month
             var productMetricsForCurrentMonth = productMetrics[currentMonth] || {};

            console.log("productMetricsForCurrentMonth ByCustomer:",productMetricsForCurrentMonth);

            // Update the table content
            var tableBody = document.getElementById("salesTable").getElementsByTagName('tbody')[0];
            tableBody.innerHTML = "";

         productMetricsForCurrentMonth.forEach(function (productMetric) {
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
        }
    </script>
    <script>
        $(document).ready(function () {
            // Handle button click
            $('.btn-view-report').on('click', function () {
                // Fetch data using Ajax
                $.ajax({
                    url: '{{ route("salescalls.view-report") }}',
                    method: 'GET',
                    dataType: 'html',
                    success: function (data) {
                        // Update modal body with fetched data
                        $('#reportModal .modal-body').html(data);

                        // Show the modal
                        $('#reportModal').modal('show');
                    },
                    error: function () {
                        // Handle errors if needed
                        alert('Error fetching data');
                    }
                });
            });
        });
    </script>
@endsection
