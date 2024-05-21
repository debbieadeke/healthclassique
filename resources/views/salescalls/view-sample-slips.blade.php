@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Sample Slips</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
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
                                <form method="GET" action="{{route('salescalls.view-sample-slips')}}">
                                    <div class="row">
                                        <label for="user_id" class="col-form-label col-2">Select Date Range</label>
                                        <div class="col-5">
                                            <div class="input-group">
                                                <input type="date" id="filter_date" class="form-control" name="filter_date" value="{{$filter_date}}">
                                                <input type="date" id="end_date" class="form-control" name="end_date" value="{{$end_date}}">
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
                            <div class="row" id="sampleSlipContainer">
                            @if (count($salescalls) > 0 || count($sampleslips) > 0 )
                                    <?php
                                    // Merge the salescalls and sampleslips collections
                                    $mergedData = $salescalls->merge($sampleslips);
                                    // Sort the merged collection by the 'created_at' date in ascending order
                                    $sortedData = $mergedData->sortBy('created_at');
                                    ?>
                                @foreach($sortedData as $index => $item)
                                    @if($item->sample_slip_image_url != null)
                                        <div class="col-md-4 pob" data-user-id="{{ $item->salesperson ? $item->salesperson->id : $item->user->id }}">
                                            <figure class="modal-trigger">
                                                @if($item->image_source === 'cloudinary' && $item->sample_slip_image_url)
                                                    <img src="{{ $item->sample_slip_image_url }}" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                                @elseif($item->image_source === 'spatie')
                                                    <img src="{{ url($item->getFirstMediaUrl('order_booked')) }}" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                                @endif
                                                <figcaption>
                                                    Posted By: {{ $item->salesperson ? $item->salesperson->first_name . ' ' . $item->salesperson->last_name : $item->user->first_name . ' ' . $item->user->last_name }}<br>
                                                    Posted On: {{\Carbon\Carbon::parse($item->created_at)->format('jS M Y g:ia')}}
                                                </figcaption>
                                            </figure>
                                            <!-- Order Modal -->
                                            <div class="modal-container">
                                                <div class="modal-content">
                                                    <span class="close-modal">&times;</span>
                                                    <img src="{{ $item->sample_slip_image_url }}" alt="Sample Image" class="modal-image">
                                                    <button class="rotate-button">Rotate</button>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No sample slips recorded on selected date period</h6></td>
                                </tr>
                            @endif
                        </div>
                        </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<style>
    /* Style for the modal trigger */
    .modal-trigger {
        cursor: pointer;
    }

    /* Style for the modal container */
    .modal-container {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        overflow: auto;
    }

    /* Style for the modal content */
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 5px;
        position: relative;
    }

    /* Style for the close button */
    .close-modal {
        color: #aaa;
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: black;
        text-decoration: none;
    }
</style>
<script>
    // Attach event listener to dropdown menu
    document.getElementById('userSelect').addEventListener('change', function() {
        console.log("Select changed")
        var userId = this.value; // Get selected user ID
        console.log(userId);
        // Hide all sample slips
        document.querySelectorAll('.sampleSlip').forEach(function(sampleSlip) {
            sampleSlip.style.display = 'none';
        });
        // Show sample slips belonging to the selected user
        if (userId) {
            document.querySelectorAll('.sampleSlip[data-user-id="' + userId + '"]').forEach(function(sampleSlip) {
                sampleSlip.style.display = 'block';
            });
        }
    });
</script>
<script>
    // Get the modal trigger elements
    var modalTriggers = document.querySelectorAll('.modal-trigger');

    // Loop through each modal trigger and add click event listener
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            // Show the corresponding modal container
            var modalContainer = this.nextElementSibling;
            modalContainer.style.display = "block";
        });
    });

    // Get the close modal elements
    var closeModals = document.querySelectorAll('.close-modal');

    // Loop through each close modal and add click event listener
    closeModals.forEach(function(closeModal) {
        closeModal.addEventListener('click', function() {
            // Hide the parent modal container
            var modalContainer = this.parentElement.parentElement;
            modalContainer.style.display = "none";
        });
    });
    var rotateButtons = document.querySelectorAll('.rotate-button');

    // Loop through each rotation button and add click event listener
    rotateButtons.forEach(function(rotateButton) {
        rotateButton.addEventListener('click', function() {
            // Get the modal content element
            var modalContent = this.parentElement;

            // Get the image element inside the modal content
            var imageElement = modalContent.querySelector('.modal-image');

            // Increment the rotation angle
            var currentRotation = imageElement.dataset.rotation ? parseInt(imageElement.dataset.rotation) : 0;
            var newRotation = (currentRotation + 90) % 360;

            // Apply the new rotation angle
            imageElement.style.transform = 'rotate(' + newRotation + 'deg)';

            // Update the rotation data attribute
            imageElement.dataset.rotation = newRotation;
        });
    });
</script>
@endsection
