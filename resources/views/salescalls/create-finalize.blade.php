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
                        <li class="breadcrumb-item active" aria-current="page">Add another Clinic Sales Call</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('salescalls.store-hospital') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-body">

                                <!-- Select Clinic -->
                                <div class="row">
                                    <div class="col-md-5">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label"><b>Select Clinic</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="displayAddNewRow()">
                                                @if ($pagetitle == "Add New Sales Call (Clinic)")
                                                    <option value="" selected>Select Clinic</option>
                                                    <option value="add_new">Add New Clinic</option>
                                                @endif
                                                @foreach($clients as $client)
                                                    <option class="form-control" value="{{$client->id}}">{{$client->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="myclass" class="form-label"><b>Class</b></label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly value="{{$returned_class}}">

                                    </div>
                                    <div class="col-md-3">
                                        <!-- Next Planned Visit -->

                                    </div>
                                </div>

                                <div class="row" id="AddFacilityRow" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                    <div class="container p-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="newfirstname" class="form-label">Clinic Name</label>
                                                <input type="text" class="form-control" id="newfacilityname" name="newfacilityname">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="newclass" class="form-label">Class</label>
                                                <select class="form-control" id="newclass" name="newclass">
                                                    <option class="form-control" value="A" selected>A</option>
                                                    <option class="form-control" value="B">B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
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
                                    <div class="col-md-1">
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
                                            <input type="text" class="form-control" id="first_name" name="first_name" aria-describedby="FirstNameHelp">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label"><b>Last Name</b></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" aria-describedby="LastNameHelp">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
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
                                <!-- Discussion Summary -->
                                <div class="mb-3">
                                    <label for="discussion_summary" class="form-label"><b>Discussion Summary</b></label>
                                    <textarea class="form-control" id="discussion_summary" name="discussion_summary" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
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
                                        <input type="hidden" name="start_time" value="{{$start_time}}">

                                        <input type="hidden" name="sales_call_id" value="{{$sales_call_id}}">

                                    <button type="submit" id="saveAnotherBtn" class="btn btn-primary" name="action" value="continue_hospital_submit" onclick="disableButton()">Save & Add another doctor</button>
                                    <button type="submit" id="finalizeBtn" class="btn btn-danger" name="action" value="finalize_hospital_submit" onclick="disableButton()">Submit & End Call</button>
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
@endsection
