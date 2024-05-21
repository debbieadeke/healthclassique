@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
						<li class="breadcrumb-item"><a href="{{route('salescalls.list-pharmacy')}}">Pharmacy Sales Calles</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Sales Call</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
			<div class="card-header">
                        <h5 class="card-title fw-semibold">View Sales Call</h5>
             </div>
            <div class="card-body">

                <div class="card">
                    <div class="card-body">
							<div class="form-body">

							<!-- Select Pharmacy -->
							<div class="row">
								<div class="col-4">
									<!-- Double Call (Drop-down) -->
									<div class="mb-3">
										<label for="client_id" class="form-label"><b>Pharmacy</b></label>
										<input type="text" id="client_id" class="form-control readonly-label" value="{{$facility_name}}" readonly>
									</div>
								</div>
                                <div class="col-4">
									<!-- Start Time -->
									<div class="mb-3">
										<label for="start_time" class="form-label"><b>Date of Visit</b></label>
										<input type="text" class="form-control readonly-label" id="start_time" name="start_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('jS M Y')
                                    }}" readonly>
									</div>
								</div>
                                <div class="col-4">
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
									<h4><b>Pharmtechs Seen</b></h4>
								</div>
							</div>
                            @foreach($salescall->salescalldetails as $salescalldetail)
                                <div class="row">
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>Pharmtech's Name</b></label>
                                            <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{isset($salescalldetail->titles->name) ? $salescalldetail->titles->name : '' }}.{{ isset($salescalldetail->first_name) ? ' ' . $salescalldetail->first_name : '' }}{{ isset($salescalldetail->last_name) ? ' ' . $salescalldetail->last_name : '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="speciality" class="form-label"><b>Pharmtech's Speciality</b></label>
                                            <input type="text" class="form-control readonly-label" id="speciality" name="speciality" value="{{ optional($salescalldetail->specialities)->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label for="contact" class="form-label"><b>Contact</b></label>
                                            <input type="text" class="form-control readonly-label" id="contact" name="contact" value="{{$salescalldetail->contact}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

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
                                    <label for="discussion_summary" class="form-label"> <b>Samples Given</b></label>
                                </div>
                            </div>
							@if (count($samples[0]) >= 1)
                                        <div class="row">
                                            @foreach($samples[0] as $key => $product_sample)
                                                <div class="col-6">
                                                    <div class="row g-1">
                                                        <div class="col-8">
                                                            <div class="mb-3">
                                                                <label for="sample_{{$loop->index}}" class="form-label"><b>Sample</b></label>
                                                                <input type="text" class="form-control readonly-label" id="sample_{{$loop->index}}" name="sample[]" value="{{$product_sample->product->name}}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="mb">
                                                                <label for="quantity_{{$loop->index}}" class="form-label"><b>Quantity </b></label>
                                                                <input type="text" class="form-control readonly-label" id="quantity_{{$loop->index}}" name="quantity[]" value="{{$product_sample->quantity}}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    <figure>
                                        @if($salescall->image_source === 'cloudinary' && $salescall->sample_slip_image_url)
                                            <img src="{{ $salescall->sample_slip_image_url }}" id="smpImg" alt="Sample slip Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                        @else
                                            @if($salescall->image_source === 'spatie' && $salescall->getFirstMediaUrl('pharmacy_audit', 'thumb'))
                                                <img src="{{ url($salescall->getFirstMediaUrl('pharmacy_audit', 'thumb')) }}" id="pobsImg" alt="Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                            @else
                                                <img src="{{ url($salescall->getFirstMediaUrl('pharmacy_audit')) }}" id="pobsImg" alt="Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
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
                                        <b>Reason:</b> {{$salescall->pharmacy_reasons_for_not_auditing}}
                                    </div>
                                </div>
                            @endif
                            <div class="row m-3">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><b>Prescription Audit</b></h4>
                                    </div>
                                </div>

                            @if ($salescall->pharmacy_prescription_audit == "Yes")
                                    <figure>
                                        @if($salescall->image_source === 'cloudinary' && $salescall->pxn_audit_image_url)
                                            <img src="{{ $salescall->pxn_audit_image_url }}" id="pxnImg" alt="Pharmacy Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                        @else
                                            @if($salescall->image_source === 'spatie' && $salescall->getFirstMediaUrl('pharmacy_audit', 'thumb'))
                                                <img src="{{ url($salescall->getFirstMediaUrl('pharmacy_audit', 'thumb')) }}" id="pobsImg" alt="Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                            @else
                                                <img src="{{ url($salescall->getFirstMediaUrl('pharmacy_audit')) }}" id="pobsImg" alt="Audit Image" class="img-fluid myImg" style="width: 300px; height: 200px">
                                            @endif
                                        @endif
                                        <figcaption>Posted By: {{$salescall->salesperson->first_name}} {{$salescall->salesperson->last_name}} <br>Posted On: {{\Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia')}}</figcaption>
                                    </figure>
                            @else
                                <div class="row">
                                    <div class="col-3">
                                        Not Done
                                    </div>
                                    <div class="col-9">
                                        <b>Reason:</b> {{$salescall->pharmacy_reasons_for_not_auditing}}
                                    </div>
                                </div>
                            @endif
                            <div class="row m-3">

                            </div>





                    </div>


                </div>

            </div>
        </div>
    </div>
@endsection
