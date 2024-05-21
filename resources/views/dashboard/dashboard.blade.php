<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                        <div class="card-body position-relative">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center mb-7">

                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="border-end pe-4 border-muted border-opacity-10">
                                            <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><a href="{{route('admin.user-report-month')}}">{{$total_sales_calls}}</a><i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h3>
                                            <p class="mb-0 text-dark">Total no of sales calls done this month</p>
                                        </div>
                                        <div class="ps-2">
                                            <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><a href="{{route('admin.user-report')}}">{{$today_sales_calls}}</a><i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h3>
                                            <p class="mb-0 text-dark">Total no of sales calls done today</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="welcome-bg-img mb-n7 text-end">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-2 d-flex align-items-stretch" style="visibility:hidden">

                </div>
                <div class="col-sm-6 col-lg-2 d-flex align-items-stretch" style="visibility:hidden">

                </div>


                <h5 class="card-title">View Employee's Performance</h5>
                <div id="container" class="container-fluid">
                    <form method="get" action="{{route('admin.user-report')}}">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Employee</label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <option value="" selected>Select Employee</option>
                                        <option value="all">All Employees</option>
                                        @foreach($users as $user)
                                            <option class="form-control" value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Date</label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="filter_date" value="{{$filter_date}}">
                                        <input type="date" class="form-control" name="end_date" value="{{$filter_date}}">
                                        <input type="hidden" name="filter" value="is_on">
                                        <button class="btn btn-primary text-info font-medium submit-form me-2" type="submit">Go!</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="container">
                <form method="get" action="{{route('home')}}">
                    <div class="row">

                        <div class="col-md-8">
                            <h5 class="card-title fw-semibold">Employees</h5>
                            <p class="card-subtitle mb-0">Total Employees Perfomance To Date</p>
                        </div>
                        <div class="col-md-3">
                            <input type="month" class="form-control" id="start" name="start" min="2023-09" value="{{$start}}" />
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary text-info font-medium submit-form me-2" type="submit">Go!</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="col-12 d-flex align-items-strech mb-3">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title d-inline-block">Employee Performance </h4> <a href="" class="float-end patient-views">Show all</a>
                    </div>
                    <div class="card-block table-dash">

                        <div class="table-responsive">
                            <table class="table mb-4 border-0 datatable custom-table">
                                <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col" class="ps-0">User</th>
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
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-semibold mb-1">{{$my_user[0]}}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-dark mb-0">{{$my_user[1]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['coverage']}}">
                                            <p class="text-dark mb-0">{{$my_user[2]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['coverage']}}">
                                            <p class="text-dark mb-0">{{$my_user[7]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['pobs']}}">
                                            <p class="text-dark mb-0">{{$my_user[3]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['pa']}}">
                                            <p class="text-dark mb-0">{{$my_user[4]}}</p>
                                        </td>
                                        <td style="background-color: {{$my_user[6]['cme']}}">
                                            <p class="text-dark mb-0">{{$my_user[5]}}</p>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
