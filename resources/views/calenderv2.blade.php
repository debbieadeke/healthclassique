@extends('layouts.app-v2')

@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Planner</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Sales Call Plans</li>
                    </ol>
                </nav>
            </div>
        </div>

        <ul class="nav nav-tabs justify-content-between bg-white" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="active nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Wed, <br>  23/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Thur, <br>  24/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Fri, <br>  25/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Sat, <br>  26/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Sun, <br>  27/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Mon, <br>  28/04</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Tue, <br>  29/04</button>
            </li>
            <li class="nav-item align-self-center" role="presentation">
                <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false"><i class="fa fa-calendar" style="
    font-size: 22px;
    color: #357e0a;
"></i>

                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                <div class="planner-list">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                </div>
                                <div><h5 class="card-title mb-1">Meet Changamwe Hospital Management</h5>
                                    <p class="card-text">09:00am | 12th Feb, 2025</p>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-primary">See Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">.jv..</div>
            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">..bkv.</div>
            <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">..bkvc.</div>
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
    <style>
        .planner .form-check-input {
            width: 1.5em;
            height: 1.5em;
        }
        .planner
        .nav-tabs .nav-link {
            border-radius: 0;
            font-size: .9rem;
            padding: 9px;
            color: black;
        }
        .planner
        .nav-tabs .nav-link:focus,
        .planner .nav-tabs .nav-link.active,
        .planner .nav-tabs .nav-link:hover {
            background-color: #000;
            border-color: transparent;
            color: aliceblue !important;
        }
        .fc-event:hover {
            background-color: #0F9747;
        }
        .fc .fc-list-event:hover td {
            background-color: #0F9747;
        }
        .fc-direction-ltr .fc-daygrid-event.fc-event-end:hover,
        .fc-direction-rtl .fc-daygrid-event.fc-event-start:hover {
            background-color: #0F9747; /* Change the background color on hover */
        }
    </style>
    @push('scripts')
        <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: { center: 'dayGridMonth,timeGridWeek,listWeek,dayGridWeek' },
                    initialView: 'listWeek',
                    editable: true,
                    dayMaxEvents: true,
                    slotMinTime: '8:00:00',
                    slotMaxTime: '19:00:00',
                    events: @json($events)

                });
                calendar.render();

                $('#calendar').fullCalendar({
                    eventClick: function(calEvent, jsEvent, view) {
                        $('#event_id').val(calEvent._id);
                        $('#appointment_id').val(calEvent.id);
                        $('#start_time').val(moment(calEvent.start).format('YYYY-MM-DD HH:mm:ss'));
                        $('#finish_time').val(moment(calEvent.end).format('YYYY-MM-DD HH:mm:ss'));
                        $('#editModal').modal();
                    }
                });

                $('#appointment_update').click(function(e) {
                    e.preventDefault();
                    var data = {
                        _token: '{{ csrf_token() }}',
                        appointment_id: $('#appointment_id').val(),
                        start_time: $('#start_time').val(),
                        finish_time: $('#finish_time').val(),
                    };

                    $.post('{{ route('planner.appointments.ajax_update') }}', data, function( result ) {
                        $('#calendar').fullCalendar('removeEvents', $('#event_id').val());

                        $('#calendar').fullCalendar('renderEvent', {
                            title: result.appointment.client.first_name + ' ' + result.appointment.client.last_name,
                            start: result.appointment.start_time,
                            end: result.appointment.finish_time
                        }, true);

                        $('#editModal').modal('hide');
                    });
                });
            });
        </script>
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
    @endpush
@endsection
