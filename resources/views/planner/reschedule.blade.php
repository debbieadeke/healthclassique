@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Reschedule Appointments</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
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
                        <form method="post" action="{{ route('planner.update_schedule',['id'=>$appointment->id]) }}">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="client" class="form-label"><b>Client Name</b></label>
                                            <input type="text" class="form-control readonly-label" id="client" name="client" value="{{ ($appointment->client && !empty(trim($appointment->client->first_name ))) ? trim($appointment->client->first_name. ' ' .$appointment->client->last_name) : (($appointment->pharmacy && !empty(trim($appointment->pharmacy->name))) ? trim($appointment->pharmacy->name) : (($appointment->facility && !empty(trim($appointment->facility->name))) ? trim($appointment->facility->name) : '')) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="notes" class="form-label"><b>Notes</b></label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" >{{ $appointment->comments }}</textarea>
                                    </div>
                                </div>


                                <div class="row pt-4">
                                    <div class="col-md-4">
                                        <!-- Next Planned Visit -->
                                        <div class="mb-3">
                                            <label for="next_planned_visit" class="form-label"><b>Next Planned Visit</b></label>
                                            <div class="input-group">
                                                <input type="datetime-local" id="next_planned_visit" name="next_planned_visit" class="form-control" value="{{$appointment->start_time}}">
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
                                                <input type="hidden" name="start_time" value="">
                                                <button type="submit" class="btn btn-primary">Update Appointment</button>
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
