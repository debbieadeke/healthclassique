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
                    <li class="breadcrumb-item active">Assign Leave Days</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('leaves.assign_user_leave',['userId'=>$user_id]) }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                        @csrf
                        <input type="hidden" name="client_code" id="client_code" value="">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-heading">
                                        <h4>Assign Leave Days(per year)</h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="year" class="form-label"><b>Select year</b></label>
                                    <select class="form-control select2" style="width: 100%; height: 40px" id="year" name="year"  required>
                                        <option value="" selected>Select year</option>
                                        <?php
                                            $currentYear = date('Y');
                                        ?>
                                        <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                                        <?php
                                            $nextYear = date('Y') + 1;
                                        ?>
                                        <option value="{{ $nextYear }}">{{ $nextYear }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="days" class="form-label"><b>Number of Days(per year)</b></label>
                                    <input type="number" class="form-control" id="days" name="days" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Add event listener for select change
document.getElementById('year').addEventListener('change', function() {
    // Hide the select element
    this.style.display = 'none';
});
</script>
@endsection
