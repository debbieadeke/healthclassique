<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                <li class="breadcrumb-item active">Manager Dashboard</li>
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->


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
<div class="row">
    @if ($teamTotals)
        @foreach($teamTotals as $teamTotal)
            @php
                $lastMonth_percentage_performance = $teamTotal['lastMonth_percentage_performance'];
                $arrowClass = $lastMonth_percentage_performance >= 0 ? 'feather-arrow-up-right' : 'feather-arrow-down-right';
                $colorClass = $lastMonth_percentage_performance >= 0 ? 'text-success' : 'text-danger';
            @endphp
            <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3">
                <div class="dash-widget">
                    <div class="dash-boxs comman-flex-center mx-auto">
                        <img src="{{ asset('assets-v2/img/icons/team2.png') }}" alt="">
                    </div>
                    <div class="dash-content dash-count text-center">
                        <h4>{{ $teamTotal['team_name'] }}</h4>
                        <h2><span class="counter-up">{{  number_format($percentagePerformance =  $teamTotal['total_target_value'] != 0 ? ($teamTotal['total_achieved_value'] /  $teamTotal['total_target_value']) * 100 : 0, 0, '.', ',') }}</span>%</h2>
                        <h6 class="passive-view"> Ksh {{ number_format($teamTotal['total_achieved_value'], 0, '.', ',') }}</h6>
                        <p>
                        <span class="passive-view">
                             <i class="feather {{ $arrowClass }} me-1 {{ $colorClass }}"></i>
                            <span class="{{ $colorClass }}">{{ $lastMonth_percentage_performance }}%</span> vs last month
                        </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3">
            <div class="dash-widget">
                <div class="dash-boxs comman-flex-center mx-auto">
                    <img src="{{ asset('assets-v2/img/icons/team2.png') }}" alt="">
                </div>
                <div class="dash-content dash-count text-center">
                    <h4>Epimol Team</h4>
                    <h2><span class="counter-up">0</span>%</h2>
                    <h6 class="passive-view"> Ksh 0</h6>
                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>0</span> vs last month</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3">
            <div class="dash-widget">
                <div class="dash-boxs comman-flex-center mx-auto">
                    <img src="{{ asset('assets-v2/img/icons/team2.png') }}" alt="">
                </div>
                <div class="dash-content dash-count text-center">
                    <h4>Oatveen Team</h4>
                    <h2><span class="counter-up">0</span>%</h2>
                    <h6 class="passive-view"> Ksh 0</h6>
                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>0</span> vs last month</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3">
            <div class="dash-widget">
                <div class="dash-boxs comman-flex-center mx-auto">
                    <img src="{{ asset('assets-v2/img/icons/team2.png') }}" alt="">
                </div>
                <div class="dash-content dash-count text-center">
                    <h4>Upcountry Team</h4>
                    <h2><span class="counter-up">0</span>%</h2>
                    <h6 class="passive-view"> Ksh 0</h6>
                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>0</span> vs last month</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3">
            <div class="dash-widget">
                <div class="dash-boxs comman-flex-center mx-auto">
                    <img src="{{ asset('assets-v2/img/icons/team2.png') }}" alt="">
                </div>
                <div class="dash-content dash-count text-center">
                    <h4>Team Tender</h4>
                    <h2><span class="counter-up">0</span>%</h2>
                    <h6 class="passive-view"> Ksh 0</h6>
                    <p><span class="passive-view"><i class="feather-arrow-up-right me-1"></i>0</span> vs last month</p>
                </div>
            </div>
        </div>
    @endif
</div>

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
        <div class="col-xl-2 col-md-6 align-self-center">
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
        <div class="col-xl-2 col-md-6 align-self-center">
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
                            <li><a class="dropdown-item" href="{{route('salescalls.create-cme')}}">New Call</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{route('salescalls.list-cme')}}">All RTD/CME Sales Call</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="doctor-list-blk">
    <div class="row">
        <div class="col-xl-2 col-md-6">
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-01.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[0]}}</span><span></span><span class="status-green">+60%</span></h4>
                    <h5>
                        Total Calls This Month</h5>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-01.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[1]}}</span><span>/100</span><span class="status-green">+60%</span></h4>
                    <h5>
                        Average Coverage (%)</h5>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-02.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[2]}}</span><span>/100</span><span class="status-pink">-20%</span></h4>
                    <h5>Average Call Rate (%)</h5>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-03.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[3]}}</span><span></span><span class="status-green">+40%</span></h4>
                    <h5>Total POBs</h5>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6 border-right-bg">
            <div class="doctor-widget border-right-bg">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-04.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[4]}}</span><span></span><span class="status-green">+50%</span></h4>
                    <h5> Total PXNs</h5>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="doctor-widget">
                <div class="doctor-box-icon flex-shrink-0">
                    <img src="{{ asset('assets-v2/img/icons/doctor-dash-04.svg') }}" alt="">
                </div>
                <div class="doctor-content dash-count flex-grow-1">
                    <h4><span class="counter-up" >{{$monthly_params[5]}}</span><span></span><span class="status-green">+50%</span></h4>
                    <h5>Total CMEs & RTDs</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-12 col-lg-6 col-xl-9">
        <div class="card">
            <div class="card-body">
                <div class="chart-title patient-visit">
                    <h4>Customer Calls By Class A/B</h4>
                    <div >
                        <ul class="nav chat-user-total">
                            <li><i class="fa fa-circle low-users" aria-hidden="true"></i>A Calls</li>
                            <li><i class="fa fa-circle current-users" aria-hidden="true"></i> B Calls</li>
                        </ul>
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
                <div id="patient-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-3 d-flex">
        <div class="card">
            <div class="card-body">
                <div class="chart-title">
                    <h4>Patient by Department</h4>
                </div>
                <div id="donut-chart-dash" class="chart-user-icon">
                    <img src="assets/img/icons/user-icon.svg" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="card-title d-inline-block">Employee Performance </h4> <a href="patients.html" class="float-end patient-views">Show all</a>
            </div>
            <div class="card-block table-responsive table-dash">
                <div class="table-dash p-2">
                    <table  class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                        <thead>
                        <tr style="font-size: 14px">
                            <th>No</th>
                            <th>Employee <br> Name</th>
                            <th>Total <br>Calls TD</th>
                            <th>Coverage<br>(%)</th>
                            <th>Call<br> Rate(%)</th>
                            <th>Daily <br>POBs</th>
                            <th>PXN <br>Audits</th>
                            <th>CMEs & <br>RTDs</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user_matrix as $key => $my_user)
                            <tr style="font-size: 10px">
                                <td>{{$loop->iteration}}</td>
                                <td style="font-size: 13px" >
                                    {{$my_user[0]}}
                                </td>
                                <td style="font-size: 13px"  class="text-danger">{{$my_user[1]}}</td>
                                <td style="font-size: 13px">{{$my_user[2]}}</td>
                                <td style="font-size: 13px">{{$my_user[7]}}</td>
                                <td style="font-size: 13px" class="text-success">{{$my_user[3]}}</td>
                                <td style="font-size: 13px">{{$my_user[4]}}</td>
                                <td style="font-size: 13px">{{$my_user[5]}}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{route('admin.user-report', ['user_id' => $my_user[9], 'report_period' => 'current_month'])}}"><i class="fa-solid fa-eye m-r-5"></i> View Employee</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
