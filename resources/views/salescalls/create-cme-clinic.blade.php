@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Create CME Call</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post"  enctype="multipart/form-data" action="{{ route('salescalls.store-clinic-cme') }}">
                            @csrf
                            <div class="form-body">
                                <!-- Select Pharmacy -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="client_id" class="form-label"><b>Select Clinic</b></label>
                                        <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getFacilityDetails();updateChart();updateTable();">
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
                                    <div class="col-md-2">
                                        <div id="class_div" style="flex-direction: column;">
                                            <label for="myclass" class="form-label"><b>Class</b></label>
                                            <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div id="doctorRowsContainer">
                                    <div id="rowContent">
                                        <div class="row pt-3">
                                            <div class="col-md-1">
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
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label"><b>First Name</b></label>
                                                    <input type="text" class="form-control" id="first_name" name="first_name[]" aria-describedby="FirstNameHelp" oninput="getUserLocation()">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label"><b>Last Name</b></label>
                                                    <input type="text" class="form-control" id="last_name" name="last_name[]" aria-describedby="LastNameHelp">
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label for="contacts" class="form-label"><b>Contacts</b></label>
                                                    <input type="text" class="form-control" id="contacts" name="contact[]" aria-describedby="ContactsHelp">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pt-3">
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
                                        <a href="#" id="addDoctorRow"><b>Add New doctor Row</b></a>
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
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
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
                                    <div class="col-md-12">
                                        <hr size=1>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="text-end">
                                        <input type="hidden" name="client_type" value="CME-C">
                                        <input type="hidden" name="longitude" id="longitude" value="">
                                        <input type="hidden" name="latitude" id="latitude" value="">
                                        @if ($pagetitle == "Add New Sales Call (CME)")
                                            <input type="hidden" name="start_time" value="{{$start_time}}">
                                        @endif
                                        <button type="submit" class="btn btn-danger" name="action" value="store_cme_submit">Submit & End Call</button>
                                        <button type="reset" class="btn btn-light-danger text-danger font-medium">
                                            Reset
                                        </button>
                                    </div>
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
@endsection
