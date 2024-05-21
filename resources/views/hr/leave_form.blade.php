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
                        <form id="leaveForm" method="post" action="{{ route('leaves.apply_leave') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-heading">
                                        <h4>Leave Request</h4>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="leave_type">Leave Type <span class="login-danger">*</span> </label>
                                        <select id="leave_type" name="leave_type" class="form-control select2" tabindex="-1" aria-hidden="true" onchange="updateDateRestriction()" required>
                                            <option value="">Leave Type</option>
                                            <option value="annual">Annual leave Day</option>
                                            <option value="medical">Medical Emergency</option>
                                            <option value="compassionate">Compassionate Leave</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="from">From <span class="login-danger">*</span></label>
                                        <input type="date" id="from" name="from" class="form-control" value="" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="to">To <span class="login-danger">*</span></label>
                                        <input type="date" id="to" name="to" class="form-control floating" value="" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-6">
                                    <div class="input-block local-forms">
                                        <label for="leaves_days">Number of Days <span class="login-danger">*</span></label>
                                        <input id="leave_days" name="leave_days" class="form-control" type="text" value="" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="input-block local-forms">
                                        <label for="reason">Leave Reason <span class="login-danger">*</span></label>
                                        <textarea id="reason" name="reason" class="form-control" rows="3" cols="30"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="input-block local-forms">
                                        @if ($leave_days)
                                            <input id="remaining_days" class="form-control" type="text" readonly="" value="Remaining Leaves: {{ $leave_days['days_allocated'] ?: 'N/a' }}">
                                        @else
                                            <input id="remaining_days" class="form-control" type="text" readonly="" value="Remaining Leaves: N/a">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="doctor-submit text-end">
                                        <button type="submit" class="btn btn-primary submit-form me-2">Submit</button>
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

        fromDateInput.value = '';
        toDateInput.value = '';

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
