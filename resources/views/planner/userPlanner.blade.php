@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Planner</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Reps Lists</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Rep Planner</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
            <style>
                .event-badge {
                    position: relative;
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    background-color: #007bff; /* Default background color */
                    color: #ffffff;
                    text-align: center;
                }

                .count {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-weight: bold;
                    font-size: 18px;
                }

                .label {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    height: 100%;
                }

                .pending, .completed, .total {
                    font-size: 13px;
                    font-weight: 600;
                }

                .pending {
                    color: #ff0000; /* Color for pending appointments */
                }
                .total {
                    color: #0b92e7; /* Color for pending appointments */
                }

                .completed {
                    color: #00ff00; /* Color for completed appointments */
                }
                .fc-event-container .fc-event-main {
                    background-color: transparent !important; /* Set background color to transparent */
                    border-color: transparent !important; /* Set border color to transparent */
                }
                .fc-event-container .fc-event, .fc-event:hover {
                    color: rgba(255, 255, 255, 0);
                    text-decoration: none;
                }
                .fc-event-container .fc-event, .fc-event-dot {
                    background-color: rgba(248, 249, 250, 0);
                    padding:20px;
                }
                .fc-event {
                    position: relative;
                    display: block;
                    font-size: 0.85em;
                    line-height: 1.4;
                    border-radius: 3px;
                    border: 1px solid rgba(248, 249, 250, 0) !important;
                    background-color: rgba(248, 249, 250, 0);
                }
                .fc-event,
                .fc-event-dot {
                    background-color: rgba(255, 255, 255, 0) !important; /* Set background color to transparent with 50% opacity */
                }
                .status {
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 10px;
                }

                .status span {
                    margin-bottom: 5px; /* Adjust spacing between spans if needed */
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        // Your calendar options here...
                        eventClick: function (info) {
                            let eventId = info.event.id;
                            // Redirect to a different page when clicking an event
                            window.location.href = '{{ route("planner.plannerInfo", ":id") }}'.replace(':id', info.event.id);

                        },
                        headerToolbar: { center: 'dayGridMonth' }, // Only display the monthly view option
                        initialView: 'dayGridMonth', // Set initial view to monthly view
                        editable: true,
                        dayMaxEvents: true,
                        slotMinTime: '8:00:00',
                        slotMaxTime: '19:00:00',
                        eventContent: function(arg) {
                            let pendingCount = arg.event.extendedProps.pending_count;
                            let completedCount = arg.event.extendedProps.completed_count;
                            let totalCount = arg.event.extendedProps.total_appointments;

                            // Construct custom HTML for the event
                            let html = '<div class="event-content">';
                            html += '<div class="status">' +
                                '<span class="total">' + totalCount + '  Total Appt</span>' +
                                '<span class="completed"> ' + completedCount + ' Completed</span>' +
                                '<span class="pending">' + pendingCount + '  Pending</span>' +
                                '</div>';
                            html += '</div>';

                            return { html: html };
                        },
                        events: @json($events)
                    });
                    calendar.render();

                });
            </script>
            <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
            <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
    @endpush


@endsection
