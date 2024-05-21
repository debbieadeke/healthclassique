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
                        <li class="breadcrumb-item active">Leave Request</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-heading">
                                        <h4>Leave Request</h4>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="leave_type">Leave Type</label>
                                        <input type="text" id="leave_type" name="leave_type" class="form-control" value="{{$leave['leave_type']}}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="from">From</label>
                                        <input type="date" id="from" name="from" class="form-control" value="{{$leave['start_date']}}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="to">To</label>
                                        <input type="date" id="to" name="to" class="form-control floating" value="{{$leave['end_date']}}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="leaves_days">Number of Days</label>
                                        <input id="leave_days" name="leave_days" class="form-control" type="text" value="{{$leave['days']}}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="input-block local-forms">
                                        <label for="reason">Leave Reason</label>
                                        <textarea id="reason" name="reason" class="form-control" rows="3" cols="30" readonly>{{$leave['reason']}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label for="remaining_days">Remaining Leaves</label>
                                        <input id="remaining_days" class="form-control" type="text" readonly value="{{ $currentYearLeave ? $currentYearLeave->days_allocated : 'N/A' }} Days">
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form id="leaveForm" method="post" action="{{ route('leaves.approve_leave',['id'=>$leave_id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-heading">
                                        <h4>Approve/ Decline Request</h4>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="input-block local-forms">
                                        <label for="comment">Leave Comment <span class="login-danger">*</span></label>
                                        <textarea id="comment" name="comment" class="form-control" rows="3" cols="30"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block select-gender">
                                        <label class="gen-label">Accept or Decline<span class="login-danger">*</span></label>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="status" class="form-check-input" value="pending" checked="">Accept
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="status" value="rejected" class="form-check-input">Decline
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="doctor-submit text-end">
                                        <button type="submit" class="btn btn-primary submit-form me-2">Submit</button>
                                        {{--                                        <button type="button" class="btn btn-primary cancel-form">Cancel</button>--}}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

@endsection
