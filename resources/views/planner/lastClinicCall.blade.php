@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h2>Sales Calls</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item"><a href="{{route('salescalls.list-doctor')}}">Planner Details</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Planner Sales Call</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title fw-semibold">View Planner Sales Call</h5>
            </div>
            <div class="card-body">

                <div class="card">
                    <div class="card-body">
                        <div class="form-body">

                            <!-- Select Clinic -->
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Double Call (Drop-down) -->
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label"><b>Clinic Name</b></label>
                                        <input type="text" id="client_id" class="form-control readonly-label" value="{{$salescall->facility->name}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <!-- Start Time -->
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label"><b>Date of Visit</b></label>
                                        <input type="text"  class="form-control readonly-label" id="start_time" name="start_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('dS F Y')
                                    }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <!-- End Time -->
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label"><b>Time of Visit</b></label>
                                        <input type="text" class="form-control readonly-label" id="end_time" name="end_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                    }} - {{
                                        \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                    }}" readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Customers Seen</b></h4>
                                </div>
                            </div>
                            @foreach($salescall->salescalldetails as $salescalldetail)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>Doctor's Name</b></label>
                                            <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{$salescalldetail->titles->name}}.  {{$salescalldetail->first_name}} {{$salescalldetail->last_name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="discussion_summary" class="form-label"><b>Discussion Summary</b></label>
                                            <textarea class="form-control readonly-label" id="discussion_summary" name="discussion_summary" rows="4" readonly>{{$salescalldetail->discussion_summary}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="discussion_summary" class="form-label"><b>Samples Given</b></label>
                                    </div>
                                </div>
                                @if (count($samples[0]) >= 1)
                                    @foreach($samples[0] as $key => $product_sample)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label">Sample</label>
                                                    <input type="text" id="first_name" class="form-control readonly-label" value="{{$product_sample->product->name}}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Quantity Given</label>
                                                    <input id="last_name" type="text" class="form-control readonly-label" value="{{$product_sample->quantity}}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                    <figure>
                                        @if($salescall->image_source === 'cloudinary' && $salescall->sample_slip_image_url)
                                            <img src="{{ $salescall->sample_slip_image_url }}" id="smpImg" alt="Sample slip Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                        @else
                                            @if($salescall->image_source === 'spatie')
                                                <img src="{{ url($salescall->getFirstMediaUrl('sample')) }}" id="sampleImg" alt="Sample Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                            @endif
                                        @endif
                                        <figcaption>Posted By: {{$salescall->salesperson->first_name}} {{$salescall->salesperson->last_name}} <br>Posted On: {{\Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia')}}</figcaption>
                                    </figure>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            No samples given
                                        </div>
                                    </div>
                                @endif
                                <div class="row m-3">
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b>Order Booking</b></h4>
                                </div>
                            </div>

                            @if ($salescall->pharmacy_order_booked == "Yes")
                                <figure>
                                    @if($salescall->image_source === 'cloudinary' && $salescall->pob_image_url)
                                        <img src="{{ $salescall->pob_image_url }}" id="pobsImg" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                    @elseif($salescall->image_source === 'spatie')
                                        <img src="{{ url($salescall->getFirstMediaUrl('order_booked')) }}" id="pobsImg" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                    @endif
                                    <figcaption>Posted By: {{$salescall->salesperson->first_name}} {{$salescall->salesperson->last_name}} <br>Posted On: {{\Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia')}}</figcaption>
                                </figure>
                            @else
                                <div class="row">
                                    <div class="col-3">
                                        Not Done
                                    </div>
                                    <div class="col-9">
                                        <b>Reason:</b> {{$salescall->pharmacy_reasons_for_not_booking}}
                                    </div>
                                </div>
                            @endif
                            <div class="row m-3">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
