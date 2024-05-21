@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h2>Planner Appointments</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Sales Call Plans</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs justify-content-between bg-white" id="myTab" role="tablist">
                    @foreach($appointmentsByDay as $day => $appointments)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }} tab-btn" id="tab-{{ $loop->index }}" data-bs-toggle="tab" data-bs-target="#tab-pane-{{ $loop->index }}" type="button" role="tab" aria-controls="tab-pane-{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $day }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @foreach($appointmentsByDay as $day => $appointments)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-pane-{{ $loop->index }}" role="tabpanel" aria-labelledby="tab-{{ $loop->index }}" tabindex="0">
                            <div class="planner-list">
                                @if($appointments->isEmpty())
                                    <p>No activity planned for this day.</p>
                                @else
                                    <table class="table table-striped">
                                        <thead>
                                        <tr style="font-weight: bold; font-size: 16px">
                                            <th>Client</th>
                                            <th>Location</th>
                                            <th>Last Visit</th>
                                            <th>Appointment time</th>
                                            <th>Status</th>
                                            <th>View More</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($appointments as $appointment)
                                            <tr style="font-size: 13px">
                                                <td>{{ $appointment->client ? $appointment->client->first_name . ' ' . $appointment->client->last_name :
                                                 ($appointment->pharmacy ? $appointment->pharmacy->name :
                                                  ($appointment->facility ? $appointment->facility->name : 'Untitled Appointment')) }}</td>
                                                <td>{{ $appointment->location_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->lastsalescall)->format('D, M d/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:ia') }} - {{ \Carbon\Carbon::parse($appointment->finish_time)->format('h:ia') }}</td>
                                                <td>
                                                    @if ($appointment->status == 'pending')
                                                        <span class="badge bg-warning text-white badge-sm">{{ $appointment->status }}</span>
                                                    @elseif ($appointment->status == 'completed')
                                                        <span class="badge bg-success text-white badge-sm">{{ $appointment->status }}</span>
                                                    @else
                                                        {{ $appointment->status }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ route('planner.lastCall', ['id' => $appointment->id]) }}"><i class="fas fa-user" style="color:deepskyblue; font-size: 18px;"></i> &nbsp; View Previous Call</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- <div class="card p-2">
                    <div id="calendar"></div>
                </div> -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <input type="hidden" name="event_id" id="event_id" value="" />
                            <input type="hidden" name="appointment_id" id="appointment_id" value="" />
                            <div class="modal-body">
                                <h4>Edit Appointment.</h4>

                                Start time:
                                <br />
                                <input type="text" class="form-control" name="start_time" id="start_time">

                                End time:
                                <br />
                                <input type="text" class="form-control" name="finish_time" id="finish_time">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <input type="button" class="btn btn-primary" id="appointment_update" value="Save">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        /* Remove vertical scroll and add border */
        .nav-tabs {
            flex-wrap: nowrap; /* Prevent tabs from wrapping to the next line */
            overflow-x: auto; /* Add horizontal scrollbar if tabs overflow */
            overflow-y: hidden; /* Hide vertical scrollbar */
            border-bottom: 2px solid #ccc; /* Add border to the bottom of the tabs */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* Optional: Add margin to the left and right of the tabs */
        .nav-tabs .nav-item {
            margin-left: 10px;
            margin-right: 10px;
        }

        /* CSS for the tabs */
        .tab-btn {
            border: none;
            background-color: transparent;
            padding: 0.5rem 1rem;
            margin: 0;
            font-size: 1rem;
            color: #000;
        }

        /* Set active tab style */
        .nav-tabs .nav-item .active {
            border-bottom: 2px solid #007bff; /* Change color of active tab bottom border */
            color: #007bff; /* Change text color of active tab */
        }
        .nav-tabs {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* Style for each tab */
        .nav-item {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
@endsection
