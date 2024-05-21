@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<script src="{{ asset('js/modal.js') }}" defer></script>
@section('content-v2')
<div class="container">
    <div class="card text-bg-light">
        <div class="card-body">
            <h1>Sales Calls</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home </a> <i class="fas fa-angle-right"></i></li>
                    <li class="breadcrumb-item"><a href="{{route('salescalls.list-pharmacy')}}">Pharmacy Sales Calls</a> <i class="fas fa-angle-right"></i></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title fw-semibold">{{$title}}</h5>
         </div>
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        <div class="container">
                            <form method="GET" action="{{route('salescalls.view-prescription-audits')}}">
                                <div class="row">
                                    <label for="user_id" class="col-form-label col-2">Select Date Range</label>
                                    <div class="col-5">
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="filter_date" value="{{$filter_date}}">
                                            <input type="date" class="form-control" name="end_date" value="{{$end_date}}">
                                            <input type="hidden" name="filter" value="is_on">
                                            <button class="btn btn-light-info text-info font-medium" type="submit">Go!</button>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-control" id="userSelect">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        <div class="row">
                            @if (count($salescalls) > 0)
                                @foreach($salescalls as $index=> $salescall)
                                    @if($salescall->pharmacy_prescription_audit == "Yes")
                                        <div class="col-md-4 pxn" data-user-id="{{ $salescall->salesperson ? $salescall->salesperson->id : $salescall->user->id }}">
                                            <figure>
                                                @if($salescall->image_source === 'cloudinary' && $salescall->pxn_audit_image_url)
                                                    <img src="{{ $salescall->pxn_audit_image_url }}" id="pxnImg" alt="Pharmacy Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                                @else
                                                    @if($salescall->image_source === 'spatie')
                                                        <img src="{{ url($salescall->getFirstMediaUrl('pharmacy_audit')) }}" id="pobsImg" alt="Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                                    @endif
                                                @endif
                                                <figcaption>Posted By: {{$salescall->salesperson->first_name}} {{$salescall->salesperson->last_name}} <br>Posted On: {{\Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia')}}</figcaption>
                                            </figure>
                                            <!-- Pharmacy Audit Modal -->
                                            <div id="myModal{{ $index }}" class="modal">
                                                <span class="close">&times;</span>
                                                <!-- Rotate button -->
                                                <button onclick="rotateImage('{{ $index }}')"> <i class="fa fa-undo" style="color: black;"></i> Rotate</button>
                                                <img class="modal-content" id="img{{ $index }}">
                                                <div id="caption{{ $index }}"></div>
                                                <!-- adding a rotate button -->
                                            </div>
                                            <hr>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No audits recorded on selected date period</h6></td>
                                </tr>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>
    </div>
<!-- adding a rotate button -->
<script defer>
    function rotateImage(index) {
        var image = document.getElementById('img' + index);
        var currentRotation = parseFloat(image.getAttribute('data-rotation')) || 0;
        currentRotation += 90;
        image.style.transform = 'rotate(' + currentRotation + 'deg)';
        image.setAttribute('data-rotation', currentRotation);
    }
</script>
<script>
    // Attach event listener to dropdown menu
    document.getElementById('userSelect').addEventListener('change', function() {
        console.log("Select changed")
        var userId = this.value; // Get selected user ID
        // Hide all sample slips
        document.querySelectorAll('.pxn').forEach(function(pxn) {
            pxn.style.display = 'none';
        });
        // Show sample slips belonging to the selected user
        if (userId) {
            document.querySelectorAll('.pxn[data-user-id="' + userId + '"]').forEach(function(pxn) {
                pxn.style.display = 'block';
            });
        }
    });
</script>
@endsection
