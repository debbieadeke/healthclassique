@php(extract($data))
@extends('layouts.app-v2')

@section('content')
<div class="container" style="max-width:100%">
    <div class="row justify-content-center">
        @role('super_admin')
            @include('dashboard.admin')
        @endrole
        @role('manager')
            @include('dashboard.manager')
        @endrole
        @role('user')

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Sales Calls
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Sales Calls Section</h5>
                        <p class="card-text">Start a new sales call : Select the type below</p>
                        <div id="container" class="container-fluid">
                            <div class="row">
                                <div class="col">
                                     <a href="{{route('salescalls.create-doctor')}}" class="btn btn-primary">Doctor</a>
                                </div>
                                <div class="col">
                                    <a href="{{route('salescalls.create')}}" class="btn btn-primary">Clinic</a>
                                </div>
                                <div class="col">
                                    <a href="{{route('salescalls.create-pharmacy')}}" class="btn btn-primary">Pharmacy</a>
                                </div>
                              </div>

                              <div class="row pt-3">
                                <div class="col">
                                    <a href="{{route('salescalls.create-cme')}}" class="btn btn-primary">RTD & CME</a>
                                </div>
                                <div class="col">

                                </div>
                                <div class="col">

                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Weekly Plans
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Weekly Plans</h5>
                        <p class="card-text">Manage plans</p>
                        <a href="{{route('planner.calendar')}}" class="btn btn-primary">Start</a>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="container mt-4">
                <form method="get" action="{{route('home')}}">
                    <div class="row">

                        <div class="col-md-8">
                            <h5 class="card-title fw-semibold">Your Performance this Month</h5>
                        </div>
                        <div class="col-md-3">
                        </div>
                        <div class="col-md-1">
                        </div>

                    </div>
                </form>
            </div>
            <div class="col-12 d-flex align-items-strech">
                <div class="card w-100">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table align-middle text-nowrap mb-0">
                                <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col">Total Calls TD</th>
                                    <th scope="col">Coverage (%)</th>
                                    <th scope="col">Call Rate (%)</th>
                                    <th scope="col">Daily POBs</th>
                                    <th scope="col">Pxn Audits</th>
                                    <th scope="col">CMEs & RTDs</th>
                                </tr>
                                </thead>
                                <tbody class="border-top">
                                @foreach($user_matrix as $key => $my_user)
                                    @if ($my_user[0] == "$user->first_name $user->last_name")
                                    <tr>
                                        <td>
                                            <p class="fs-3 text-dark mb-0">{{$my_user[1]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['coverage']}}">
                                            <p class="fs-3 text-dark mb-0">{{$my_user[2]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['coverage']}}">
                                            <p class="fs-3 text-dark mb-0">{{$my_user[7]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['pobs']}}">
                                            <p class="fs-3 text-dark mb-0">{{$my_user[3]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['pa']}}">
                                            <p class="fs-3 text-dark mb-0">{{$my_user[4]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['cme']}}">
                                            <p class="fs-3 text-dark mb-0">{{$my_user[5]}}</p>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

    </div>
</div>
@endsection

@section('content-v2')

    @role('user')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->


{{--    <div class="good-morning-blk">--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="morning-user">--}}
{{--                    <h2>Hi, <span>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span></h2>--}}
{{--                    <p>Have a nice day at work</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-6 position-blk">--}}
{{--                <div class="morning-img">--}}
{{--                    <img src="{{ asset('assets-v2/img/morning-img-02.png') }}" alt="" class="d-none d-md-block">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div id="carouselExampleAutoplaying" class="carousel carousel-fade slide " data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets-v2/banners/HC-App-Banners-OAT.jpg" class="w-100 mb-4 d-none d-lg-block" alt="">
                <img src="assets-v2/banners/HC-App-Banners-OAT (1).jpg" class="w-100 mb-4 d-lg-none" alt="">
            </div>
            <div class="carousel-item">
                <img src="assets-v2/banners/HC-App-Banners-Zelaton.jpg" class="w-100 mb-4" alt="">
            </div>
            <div class="carousel-item">
                <img src="assets-v2/banners/HC-App-Banners-Epimol.jpg" class="w-100 mb-4" alt="">
            </div>
        </div>
    </div>
    @endrole

    @role('user')
    <div class="doctor-list-blk">
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-content dash-count flex-grow-1">
                        <h4><span class="" >Sales Calls Section</span><span class="status-green"></span></h4>
                        <h5>
                            Start a new sales call : Select the call type </h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6  align-self-center">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-content dash-count flex-grow-1">
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary submit-form me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">  Doctor Calls</button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('salescalls.create-doctor')}}">New Doctor Call</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.list-doctor')}}">All Doctor Sales Call</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div  class="col-xl-2 col-md-6 align-self-center">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-content dash-count flex-grow-1">
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary submit-form me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">  Clinic Calls</button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('salescalls.create')}}">New Call</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.list')}}">All Clinic Sales Call</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6 align-self-center">
                <div class="doctor-widget border-right-bg">
                    <div class="doctor-content dash-count flex-grow-1">
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary submit-form me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">  Pharmacy Calls</button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('salescalls.create-pharmacy')}}">New Pharmacy Call</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.list-pharmacy')}}">All Pharmacy Sales Call</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-6 align-self-center">
                <div class="doctor-widget">
                    <div class="doctor-content dash-count flex-grow-1">
                        <!-- Example single danger button -->
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary submit-form me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">  RTD & CME Calls</button>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('salescalls.create-cme')}}">Doctor RTD/CME</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.create-cme-clinic')}}">Clinic RTD/CME</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.create-cme-pharmacy')}}">Pharmacy RTD/CME</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{route('salescalls.list-cme')}}">Total RTD/CME </a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="doctor-list-blk">
            @foreach($user_matrix as $key => $my_user)
                @if ($my_user[0] == "$user->first_name $user->last_name")
            <div class="row">
                <div class="col-xl-2 col-md-6 col-sm-6">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-01.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[1]}}</span><span></span>
                                </h4>
                            <h5>
                                Total Calls TD</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-01.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[2]}}</span><span>/100</span></h4>
                            <h5>
                                Coverage (%)</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-02.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[7]}}</span><span>/100</span></h4>
                            <h5>Call Rate (%)</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-03.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[3]}}</span><span></span></h4>
                            <h5>Daily POBs</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6 border-right-bg">
                    <div class="doctor-widget border-right-bg">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-04.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[4]}}</span><span></span></h4>
                            <h5> Pxn Audits</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-6 col-sm-6">
                    <div class="doctor-widget">
                        <div class="doctor-box-icon flex-shrink-0">
                            <img src="{{ asset('assets-v2/img/icons/doctor-dash-04.svg') }}" alt="">
                        </div>
                        <div class="doctor-content dash-count flex-grow-1">
                            <h4><span class="counter-up" >{{$my_user[5]}}</span><span></span></h4>
                            <h5> CMEs & RTDs</h5>
                        </div>
                    </div>
                </div>
            </div>
                @endif
            @endforeach

        </div>
        <div class="row">
            <div class="col-12 col-md-8 col-lg-12 col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-title patient-visit mb-0">
                            <h4>Calls</h4>
                            <div class="income-value" >
                                <h3><span></span> {{$chart_one_total}}</h3>

                            </div>
                            <div class="input-block mb-0">
                                <select class="form-control select">
                                    <option>2022</option>
                                    <option>2021</option>
                                    <option>2020</option>
                                    <option>2019</option>
                                </select>
                            </div>
                        </div>
                        <div id="apexcharts-area"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-6 col-xl-3 d-flex">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="chart-title"><b>Sales PM/Monthly</b></div>
                        <div class="d-flex mx-auto" id="progressContainer" style="width: 130px; height: 130px;"></div>
                        <div class="row ">
                            <div class="d-flex p-4"><a href="{{ route('sale.userMonthlyReport') }}"  class="btn btn-primary mx-auto">View More</a></div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
        <div class="row">
            <div class="col-12 col-md-12  col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="chart-title patient-visit">
                            <h4>Activity Chart</h4>
                            <div >
                                <ul class="nav chat-user-total">
                                    <li><i class="fa fa-circle low-users" aria-hidden="true"></i>A Calls</li>
                                    <li><i class="fa fa-circle current-users" aria-hidden="true"></i> B Calls</li>
                                </ul>
                            </div>
                            <div class="input-block mb-0">
                                <select class="form-control select">
                                    <option>This Week</option>
                                    <option>Last Week</option>
                                    <option>This Month</option>
                                    <option>Last Month</option>
                                </select>
                            </div>
                        </div>
                        <div id="activity-chart"></div>
                    </div>
                </div>
                <div class="row" style="visibility: hidden">
                    <div class="col-12 col-md-12  col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title d-inline-block">Recent Appointments</h4> <a href="appointments.html" class="patient-views float-end">Show all</a>
                            </div>
                            <div class="card-body p-0 table-dash">
                                <div class="table-responsive">
                                    <table class="table mb-0 border-0 custom-table">
                                        <tbody>
                                        <tr>
                                            <td class="table-image appoint-doctor">
                                                <img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-02.jpg" alt="">
                                                <h2>Dr.Jenny Smith</h2>
                                            </td>
                                            <td class="appoint-time text-center">
                                                <h6>Today 5:40 PM</h6>
                                                <span>Typoid Fever</span>
                                            </td>
                                            <td>
                                                <button class="check-point status-green me-1"><i class="feather-check"></i></button>
                                                <button class="check-point status-pink "><i class="feather-x"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-image appoint-doctor">
                                                <img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-03.jpg" alt="">
                                                <h2>Dr.Angelica Ramos</h2>
                                            </td>
                                            <td class="appoint-time text-center">
                                                <h6>Today 5:40 PM</h6>
                                                <span>Typoid Fever</span>
                                            </td>
                                            <td>
                                                <button class="check-point status-green me-1"><i class="feather-check"></i></button>
                                                <button class="check-point status-pink "><i class="feather-x"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-image appoint-doctor">
                                                <img width="28" height="28" class="rounded-circle" src="assets/img/profiles/avatar-04.jpg" alt="">
                                                <h2>Dr.Martin Doe</h2>
                                            </td>
                                            <td class="appoint-time text-center">
                                                <h6>Today 5:40 PM</h6>
                                                <span>Typoid Fever</span>
                                            </td>
                                            <td>
                                                <button class="check-point status-green me-1"><i class="feather-check"></i></button>
                                                <button class="check-point status-pink "><i class="feather-x"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12  col-xl-4 d-flex">
                        <div class="card wallet-widget">
                            <div class="circle-bar circle-bar2">
                                <div class="circle-graph2" data-percent="66">
                                    <b><img src="assets/img/icons/timer.svg" alt=""></b>
                                </div>
                            </div>
                            <div class="main-limit">
                                <p>Next Appointment in</p>
                                <h4>02h:12m</h4>
                            </div>
                        </div>
                    </div>
                </div>


    @endrole

    @role('super_admin')
        @include('dashboard.admin')
    @endrole

    @role('manager')
        @include('dashboard.manager')
    @endrole

    @role('customer_admin')
        @include('dashboard.customer-admin')
    @endrole

    @role('store_manager')
        @include('dashboard.store-manager')
    @endrole

@endsection
<style>
    .donut {
        position: relative;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background-color: #ffffff;
        overflow: hidden;
        text-align: center;
    }

    .donut .donut-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 30px solid #eee; /* Adjust the border thickness */
        border-radius: 50%;
        box-sizing: border-box;
    }

    .donut .donut-segment {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        clip-path: inset(0 50% 0 0); /* Adjust clip to form a donut shape */
        box-sizing: border-box;
        border: 30px solid transparent; /* Start with transparent border */
        transition: clip-path 0.5s, border-top-color 0.5s; /* Smooth transition for clip-path and color change */
    }

    .donut .donut-text {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        transform: translateY(-50%);
        font-family: Arial, sans-serif;
    }
    .progressbar-text {
        font-weight: 900;
        font-size: 15px;
        text-anchor: middle;
        dominant-baseline: middle;
        fill: #1a1a1a;
        transform: translate(0, 0.3em);
    }
    html, body {
        height: 100%;
        margin: 0;
    }
    .apexcharts-datalabel-label  {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 13px;
        color: blue;
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
        .doctor-content h4 span:last-child {
            margin: 0 ;
        }
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>
<script>
    window.onload = function() {
        const performance = parseFloat("{{ $performance }}"); // Assuming $performance is a float value

        // Clamp performance value to ensure it stays within the range of 0 to 100%
        const clampedPerformance = Math.min(Math.max(performance, 0), 100);

        const progressBar = new ProgressBar.Circle('#progressContainer', {
            strokeWidth: 13, // Adjust the thickness of the progress bar
            trailColor: '#f3f3f3', // Color of the trail (background)
            trailWidth: 13, // Adjust the thickness of the trail
            easing: 'easeInOut', // Easing function for animation
            duration: 1500, // Duration of the animation in milliseconds
            text: {
                value: '', // Initial text value (empty)
                className: 'progressbar-text' // Custom class for text styling
            },
            from: { color: '#4CAF50', width: 13 },
            to: { color: '#4CAF50', width: 13 },
            step: function(state, circle) {
                // Set color based on performance value
                let color = '#4CAF50'; // default color
                if (clampedPerformance < 70) {
                    color = '#FF5733'; // red color for performance below 70%
                }

                circle.path.setAttribute('stroke', color);
                circle.path.setAttribute('stroke-width', state.width);

                const value = Math.round(performance); // Adjust the progress value
                circle.setText(`PM / M\n${value}%`); // Update text content
            }
        });

        progressBar.animate(clampedPerformance / 100); // Set the progress value as a percentage
    };
</script>
