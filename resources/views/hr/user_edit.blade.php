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
                        <form id="editLeaveForm" method="post" action="{{ route('leaves.edit_leave',['id'=>$leave['id']]) }}" enctype="multipart/form-data">
                            @csrf
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
                                        <input type="date" id="from" name="from" class="form-control" value="{{ $leave['start_date'] }}" {{ $leave['statuz'] == 'approved' ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="to">To</label>
                                        <input type="date" id="to" name="to" class="form-control floating" value="{{$leave['end_date']}}" {{ $leave['statuz'] == 'approved' ? 'readonly' : '' }}>
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
                                        <textarea id="reason" name="reason" class="form-control" rows="3" cols="30" {{ $leave['statuz'] == 'approved' ? 'readonly' : '' }}>{{$leave['reason']}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        <label for="remaining_days">Remaining Leaves</label>
                                        <input id="remaining_days" class="form-control" type="text" readonly value="{{ $currentYearLeave ? $currentYearLeave->days_allocated : 'N/A' }} Days">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="doctor-submit text-end">
                                        <button type="submit" class="btn btn-primary submit-form me-2">Edit</button>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get references to the 'From' and 'To' input fields
        const fromDateInput = document.getElementById('from');
        const toDateInput = document.getElementById('to');

        // Add event listeners to 'from' and 'to' input fields to calculate the difference
        fromDateInput.addEventListener('change', calculateDifference);
        toDateInput.addEventListener('change', calculateDifference);
    });

    function calculateDifference() {
        const fromDate = new Date(document.getElementById('from').value);
        const toDate = new Date(document.getElementById('to').value);

        // Calculate the difference in days, and add 1 to include the first day
        const differenceInTime = toDate.getTime() - fromDate.getTime();
        const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24)) + 1;

        // Set the calculated difference as the value of the 'Number of Days' input field
        document.getElementById('leave_days').value = differenceInDays;
    }
</script>

<script>
    function updateDateRestriction() {
        const leaveTypeSelect = document.getElementById('leave_type');
        const fromDateInput = document.getElementById('from');
        const toDateInput = document.getElementById('to');

        // Clear any date restrictions
        fromDateInput.removeAttribute('min');
        toDateInput.removeAttribute('min');

        if (leaveTypeSelect.value === 'annual') {
            const currentDate = new Date();
            const minDate = new Date(currentDate.getTime() + 10 * 24 * 60 * 60 * 1000); // 10 days from current date
            const minDateString = minDate.toISOString().split('T')[0];
            fromDateInput.setAttribute('min', minDateString);
            toDateInput.setAttribute('min', minDateString);
        } else {
            const currentDate = new Date();
            const minDateString = currentDate.toISOString().split('T')[0];
            // Reset max attribute if leave type is not annual
            fromDateInput.setAttribute('min', minDateString);
            toDateInput.setAttribute('min', minDateString);
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        updateDateRestriction(); // Call the function once when the page loads


        const leaveTypeSelect = document.getElementById('leave_type');
        leaveTypeSelect.addEventListener('change', updateDateRestriction); // Call the function when leave_type changes
    });
</script>

@endsection
