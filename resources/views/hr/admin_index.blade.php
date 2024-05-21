@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="content">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Leaves</h4>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">Leave Requests</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card inovices-card">
                    <div class="card-body">
                        <p class="inovices-all">Annual Leaves</p>
                        <div class="inovices-dash-count">
                            <div class="inovices-amount">{{$annual}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card inovices-card">
                    <div class="card-body">
                        <p class="inovices-all">Medical Leave</p>
                        <div class="inovices-dash-count">
                            <div class="inovices-amount">{{$medical}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card inovices-card">
                    <div class="card-body">
                        <p class="inovices-all">Compassionate</p>
                        <div class="inovices-dash-count">
                            <div class="inovices-amount">{{$compassionate}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card inovices-card">
                    <div class="card-body">
                        <p class="inovices-all">Other</p>
                        <div class="inovices-dash-count">
                            <div class="inovices-amount">{{$other}}</div>
                        </div>
                    </div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
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
