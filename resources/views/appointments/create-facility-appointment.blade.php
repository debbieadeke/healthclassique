@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Clinic Appointment</h1>
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
                        <form method="post" action="{{ route('planner.store-facility-appointment') }}">
							@csrf
							<div class="form-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <!-- Double Call (Drop-down) -->
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label"><b>Select Facility</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getFacilityDetails()">
                                                    <option value="" selected>Select Facility</option>
                                                @foreach($facilities as $client)
                                                    <option class="form-control" value="{{$client->id}}" data-extra-class="{{$client->class}}">{{$client->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="myclass" class="form-label"><b>Class</b></label>
                                        <input type="text" class="form-control" id="myclass" name="myclass" readonly>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">

                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="AddFacilityRowDiv" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                    <div class="container p-3">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="newfirstname" class="form-label">Facility Name</label>
                                                <input type="text" class="form-control" id="newfacilityname" name="newfacilityname">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="newclass" class="form-label">Class</label>
                                                <select class="form-control" id="newclass" name="newclass">
                                                    <option class="form-control" value="A" selected>A</option>
                                                    <option class="form-control" value="B">B</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="newtype" class="form-label">Type</label>
                                                <select class="form-control" id="newtype" name="newtype">
                                                    <option value="Clinic" selected>Clinic</option>
                                                    <option value="Pharmacy">Pharmacy</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
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
                                      <label for="next_planned_visit" class="form-label"><b>Next Planned Visit</b></label>
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
                                      <input type="hidden" name="source" value="facility">
                                      <input type="hidden" name="start_time" value="{{$start_time}}">
                                    <button type="submit" class="btn btn-primary">Save Appointment</button>
                                    <button type="reset" class="btn btn-light-danger text-danger font-medium">Reset</button>
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
