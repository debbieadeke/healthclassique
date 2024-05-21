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
                                                            <a class="dropdown-item" href="{{ route('planner.lastCall', ['id' => $appointment->id]) }}"><i class="fas fa-eye" style="color:deepskyblue; font-size: 12px;"></i> &nbsp; View Previous Call</a>
                                                            <a class="dropdown-item" href="{{ route('planner.reschedule', ['id' => $appointment->id]) }}" ><i class="fas fa-calendar-check" style="color:orangered; font-size: 12px;"></i> &nbsp; Reschedule Appointment</a>
                                                            @if ($appointment->client)
                                                                <a class="dropdown-item" href="{{ route('salescalls.create-doctor') }}"><i class="fas fa-phone" style="color: darkgreen; font-size: 12px;"></i> &nbsp; Make A Call</a>
                                                            @elseif ($appointment->pharmacy)
                                                                <a class="dropdown-item" href="{{route('salescalls.create-pharmacy')}}"><i class="fas fa-phone" style="color: darkgreen; font-size: 12px;"></i> &nbsp; Make A Call</a>
                                                            @elseif ($appointment->facility)
                                                                <a class="dropdown-item" href="{{ route('salescalls.create') }}"><i class="fas fa-phone" style="color: darkgreen; font-size: 12px;"></i> &nbsp; Make A Call</a>
                                                            @endif
                                                            <a class="dropdown-item" href="{{route('planner.create-appointment')}}"><i class="fas fa-plus" style="color:deepskyblue; font-size: 12px;"></i> &nbsp; Add Doctor Appointment</a>
                                                            <a class="dropdown-item" href="{{route('planner.create-facility-appointment')}}"><i class="fas fa-plus" style="color:deepskyblue; font-size: 12px;"></i> &nbsp; Add Clinic Appointment</a>
                                                            <a class="dropdown-item" href="{{route('planner.create-pharmacy-appointment')}}"><i class="fas fa-plus" style="color:deepskyblue; font-size: 12px;"></i> &nbsp; Add Pharmacy Appointment</a>
                                                            <span class="dropdown-item">
                                                                <form action="{{ route('planner.destroy_appointment', ['id' => $appointment->id]) }}" method="POST" id="deleteForm{{$appointment->id}}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id" value="{{ $appointment->id }}"/>
                                                                    <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Appointment?');" style="padding: 0;">
                                                                        <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 12px;"></i> Delete Appointment
                                                                    </button>
                                                                </form>
                                                             </span>
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
