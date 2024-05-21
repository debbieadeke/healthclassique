@extends('layouts.app-v2')

@section('content-v2')
<div class="container">
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
        <div class="card p-2">
			<div id="calendar"></div>
		</div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <input type="hidden" name="event_id" id="event_id" value="" />
                    <input type="hidden" name="appointment_id" id="appointment_id" value="" />
                    <div class="modal-body">
                        <h4>Edit Appointment</h4>

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
