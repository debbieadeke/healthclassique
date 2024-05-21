@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
<div class="content">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Leave Requests</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table show-entire">
                <div class="card-body">

                    <div class="page-table-header mb-2">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="doctor-table-blk">
                                    <h3>Leave Request</h3>
{{--                                    <div class="doctor-search-blk">--}}
{{--                                        <div class="top-nav-search table-search-blk">--}}
{{--                                            <form>--}}
{{--                                                <input type="text" class="form-control" placeholder="Search here">--}}
{{--                                                <a class="btn"><img src="{{asset('assets-v2/img/icons/search-normal.svg')}}" alt=""></a>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                        <div class="add-group">--}}
{{--                                            <a href="add-leave.html" class="btn btn-primary add-pluss ms-2"><img src="{{asset('assets-v2/img/icons/plus.svg')}}" alt=""></a>--}}
{{--                                            <a href="javascript:;" class="btn btn-primary doctor-refresh ms-2"><img src="{{asset('assets-v2/img/icons/re-fresh.svg')}}" alt=""></a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
{{--                            <div class="col-auto text-end float-end ms-auto download-grp">--}}
{{--                                <a href="javascript:;" class=" me-2"><img src="{{asset('assets-v2/img/icons/pdf-icon-01.svg')}}" alt=""></a>--}}
{{--                                <a href="javascript:;" class=" me-2"><img src="{{asset('assets-v2/img/icons/pdf-icon-02.svg')}}" alt=""></a>--}}
{{--                                <a href="javascript:;" class=" me-2"><img src="{{asset('assets-v2/img/icons/pdf-icon-03.svg')}}" alt=""></a>--}}
{{--                                <a href="javascript:;"><img src="{{asset('assets-v2/img/icons/pdf-icon-04.svg')}}" alt=""></a>--}}
{{--                            </div>--}}
                        </div>
                    </div>

{{--                    <div class="staff-search-table">--}}
{{--                        <form>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="input-block local-forms">--}}
{{--                                        <label>Employee Name </label>--}}
{{--                                        <input class="form-control" type="text">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="input-block local-forms">--}}
{{--                                        <label>Leave Type </label>--}}
{{--                                        <select class="form-control select select2-hidden-accessible" tabindex="-1" aria-hidden="true">--}}
{{--                                            <option>Select Leave Type</option>--}}
{{--                                            <option>Medical Leave</option>--}}
{{--                                            <option>Casual Leave</option>--}}
{{--                                            <option>Loss of Pay</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="input-block local-forms">--}}
{{--                                        <label>Leave Status </label>--}}
{{--                                        <select class="form-control select select2-hidden-accessible" tabindex="-1" aria-hidden="true">--}}
{{--                                            <option>Leave Status</option>--}}
{{--                                            <option>Pending</option>--}}
{{--                                            <option>Approved</option>--}}
{{--                                            <option>Declined</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="input-block local-forms cal-icon">--}}
{{--                                        <label>From </label>--}}
{{--                                        <input class="form-control datetimepicker" type="text">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="input-block local-forms cal-icon">--}}
{{--                                        <label>To </label>--}}
{{--                                        <input class="form-control datetimepicker" type="text">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-12 col-md-6 col-xl-4">--}}
{{--                                    <div class="doctor-submit">--}}
{{--                                        <button type="submit" class="btn btn-primary submit-list-form me-2">Search</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table class="table border-0 custom-table comman-table datatable mb-0 dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Employee Name: activate to sort column ascending" style="width: 138.547px;">Employee Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Leave Type: activate to sort column ascending" style="width: 85.4531px;">Leave Type</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="From: activate to sort column ascending" style="width: 56.875px;">From</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="To: activate to sort column ascending" style="width: 56.875px;">To</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="No of days: activate to sort column ascending" style="width: 74.7344px;">No of days</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Reason: activate to sort column ascending" style="width: 112.484px;">Reason</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 88px;">Status</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label=": activate to sort column ascending" style="width: 16px;"></th></tr>
                                        </thead>
                                        <tbody>
                                        @foreach($leaves as $leave)
                                        <tr role="row" class="odd">
                                            <td class="profile-image">
                                                {{ $leave->user->first_name }}  {{ $leave->user->last_name }}
                                            </td>
                                            <td>{{ $leave->leave_type }}</td>
                                            <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('jS F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('jS F Y') }}</td>
                                            <td>{{ $leave->days }} Day(s)</td>
                                            <td>{{ \Illuminate\Support\Str::words($leave->reason, 5, '...') }}</td>
                                            <td>
                                                @if($leave->statuz == 'pending')
                                                    <a class="custom-badge status-orange">
                                                        {{ $leave->statuz }}
                                                    </a>
                                                @elseif($leave->statuz == 'accepted')
                                                    <a class="custom-badge status-green">
                                                        {{ $leave->statuz }}
                                                    </a>
                                                @elseif($leave->statuz == 'new')
                                                    <a class="custom-badge status-blue">
                                                        {{ $leave->statuz }}
                                                    </a>
                                                @elseif($leave->statuz == 'rejected')
                                                    <a class="custom-badge status-pink">
                                                        {{ $leave->statuz }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{route('leaves.show_leave', ['id' => $leave->id])}}"><i class="fa-solid fa-eye m-r-5"></i> View</a>
                                                        <a class="dropdown-item" href="#"><i class="fa-solid fa-pen-to-square m-r-5"></i> Edit</a>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                       </tbody>
                                    </table>
                                </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
