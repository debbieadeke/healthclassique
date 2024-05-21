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
                        <form method="post" action="{{ route('planner.store-appointment') }}">
                            @csrf
                            <div class="form-body">

                                <!-- Select Clinic -->
                                <!-- Select Clinic -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label">Select Doctor</label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getUserDetails()">
                                                <option value="" selected>Select Doctor</option>
                                                @foreach($clients as $client)

                                                        <option class="form-control" value="{{$client->id}}" data-extra-info="None" data-extra-class="{{$client->class}}">{{$client->titles->name}}.  {{$client->first_name}} {{$client->last_name}}
                                                            @if ($visited_today)
                                                                (Visited today)
                                                            @endif
                                                            @if ($in_appointment)
                                                                (In Appointment)
                                                            @endif
                                                        </option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div id="speciality_div" style="flex-direction: column;">
                                            <label for="speciality" class="form-label">Speciality</label>
                                            <input type="text" class="form-control" id="speciality" name="speciality" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div id="class_div" style="flex-direction: column;">
                                            <label for="myclass" class="form-label">Class</label>
                                            <input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>

                                <div class="row" id="AddDoctorRow" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                    <div class="container p-3">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="mb-3">
                                                    <label for="client_id" class="form-label">Title</label>
                                                    <select class="form-control" id="title_id" name="title_id">
                                                        @foreach($titles as $title)
                                                            <option class="form-control" value="{{$title->id}}">{{$title->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="newfirstname" class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="newfirstname" name="newfirstname">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="newlastname" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="newlastname" name="newlastname">
                                            </div>
                                            <div class="col-md-1">
                                                &nbsp;
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="newclass" class="form-label">Class</label>
                                                <select class="form-control" id="newclass" name="newclass">
                                                    <option class="form-control" value="A" selected>A</option>
                                                    <option class="form-control" value="B">B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="newspeciality" class="form-label">Speciality</label>
                                                <select class="form-control" id="newspeciality" name="newspeciality">
                                                    <option value="" selected>Select</option>
                                                    @foreach($specialities as $speciality)
                                                        <option class="form-control" value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                    @endforeach
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
                                    <div class="col-md-4">
                                        <!-- Next Planned Visit -->
                                        <div class="mb-3">
                                            <label for="next_planned_visit" class="form-label">Next Planned Visit</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" value="" name="next_planned_visit">
                                                <input type="time" class="form-control" value="08:30:00" name="next_planned_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">

                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-actions">
                                            <div class="text-end align-baseline">
                                                <input type="hidden" name="source" value="client">
                                                <input type="hidden" name="start_time" value="{{$start_time}}">
                                                <button type="submit" class="btn btn-primary">Save Appointment</button>
                                                <button type="reset" class="btn btn-light-danger text-danger font-medium">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
