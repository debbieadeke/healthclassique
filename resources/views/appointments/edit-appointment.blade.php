@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Appointments</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reschedule</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('planner.update-appointment') }}">
							@csrf
							<div class="form-body">

							<!-- Select Clinic -->
                                <!-- Select Clinic -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="client_id" class="form-label">Select Appointment</label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="appointment_id" name="appointment_id" required onchange="getUserDetails()">
                                                <option value="" selected>Select Appointment</option>

                                                @foreach($appointments as $appointment)
                                                        @if (isset($appointment->client))
                                                        <option class="form-control" value="{{$appointment->id}}">{{$appointment->client->first_name . ' ' . $appointment->client->last_name}} Appointment planned for  {{ Carbon\Carbon::parse($appointment->start_time)->format('d-M-Y') }}
                                                                                                              </option>
                                                        @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                </div>

                                <div class="row">
                                <div class="col-md-7">
                                    <!-- Next Planned Visit -->
                                    <div class="mb-3">
                                      <label for="next_planned_visit" class="form-label">Updated Planned Visit</label>
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
                                    <button type="submit" class="btn btn-primary">Update Appointment</button>
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
