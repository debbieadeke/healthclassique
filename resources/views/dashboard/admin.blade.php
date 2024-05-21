<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                <li class="breadcrumb-item active">Admin Dashboard</li>
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
                    <h4>Sales</h4>
                </div>
                <div id="chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 col-xl-3 d-flex">
        <div class="card">
            <div class="card-body">
                <div class="chart-title">
                    @if($currentMonth !== null)
                        <h4>{{ $months[$currentMonth] }}  Sales PM%</h4>
                    @endif
                </div>
                <div class="d-flex mx-auto" id="progressContainer" style="width: 200px; height: 200px;"></div>
                <div class="row pt-4">
                    <div class="d-flex"><h5><span class="fw-bold">Total Sales:</span>&nbsp;ksh &nbsp;{{ number_format($totalsales, 2, '.', ',') }}</h5></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title fw-semibold">Employee Performance </h4>
{{--                <a href="#" class="float-end patient-views">Show all</a>--}}
                <div class="filter-section d-flex">
                    <form id="filterForm" action="{{route('employee_performance_filter')}}" method="GET" class="d-flex">
                        <div class="mb-3 me-3">
                            <select class="form-select" id="month" name="month">
                                @foreach($months as $key => $month)
                                    <option name="selected_month" value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
    .table td {
        padding: 0.001rem;
        vertical-align: middle;
        line-height: 0.1;
    }
</style>
<script>
    // Your PHP-generated data
    var data = {!! json_encode(array_values($monthlyTotals)) !!};
    var categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var currentYear = <?php echo date('Y'); ?>;

    // Create the Highcharts chart
    Highcharts.chart('chart', {
        chart: {
            type: 'column' // Use 'column' type for vertical bars
        },
        title: {
            text: 'Monthly Sales ' + currentYear // Include current year in the title
        },
        xAxis: {
            categories: categories,
            title: {
                text: 'Months'
            }
        },
        yAxis: {
            title: {
                text: 'Sales (Ksh)'
            },
            labels: {
                formatter: function() {
                    return 'Ksh ' + Intl.NumberFormat().format(this.value); // Format y-axis labels with comma separators and prefix with "Ksh"
                }
            }
        },
        tooltip: {
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' + 'Ksh ' + Intl.NumberFormat().format(this.y); // Add "Ksh" prefix to tooltip
            }
        },
        plotOptions: {
            series: {
                pointWidth: 30, // Adjust the width of the bars
                colorByPoint: true
            }
        },
        legend: {
            enabled: false // Disable the legend
        },
        series: [{
            name: 'Sales',
            data: data
        }]
    });
</script>

<script>
    window.onload = function() {

        const performance = parseFloat("{{ $companyPerformance }}"); // Assuming $performance is a float value

        // Clamp performance value to ensure it stays within the range of 0 to 100%
        const clampedPerformance = Math.min(Math.max(performance, 0), 100);

        const progressBar = new ProgressBar.Circle('#progressContainer', {
            strokeWidth: 17, // Adjust the thickness of the progress bar
            trailColor: '#f3f3f3', // Color of the trail (background)
            trailWidth: 17, // Adjust the thickness of the trail
            easing: 'easeInOut', // Easing function for animation
            duration: 1500, // Duration of the animation in milliseconds
            text: {
                value: '', // Initial text value (empty)
                className: 'progressbar-text' // Custom class for text styling
            },
            from: { color: '#4CAF50', width: 17 },
            to: { color: '#4CAF50', width: 17 },
            step: function(state, circle) {
                // Set color based on performance value
                let color = '#4CAF50'; // default color
                if (clampedPerformance < 70) {
                    color = '#FF5733'; // red color for performance below 70%
                }

                circle.path.setAttribute('stroke', color);
                circle.path.setAttribute('stroke-width', state.width);

                const value = Math.round(performance); // Adjust the progress value
                circle.setText(`<tspan class="text-info-emphasis fw-bold"><h3>${value}%</h3></tspan>`); // Update text content
            }
        });

        progressBar.animate(clampedPerformance / 100); // Set the progress value as a percentage
    };
</script>

