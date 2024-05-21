@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Sales Doctor Calls</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item"><a href="{{route('salescalls.list-doctor')}}">Planner Details</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Last Sales Call</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title fw-semibold">View Doctor Sales Call</h5>
            </div>
            <div class="card-body">

                <div class="card">
                    <div class="card-body">
                        <div class="form-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label"><b>Doctor's Name</b></label>
                                        <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{$salescall->client->titles->name}}.  {{$salescall->client->first_name}} {{$salescall->client->last_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="speciality" class="form-label"><b>Speciality</b></label>
                                        <input type="text" class="form-control readonly-label" id="speciality" name="speciality" value="{{$salescall->client->specialities->name}}">

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="location" class="form-label"><b>Location</b></label>
                                        <input type="text" class="form-control readonly-label" id="location" name="location" value="{{$salescall->client->locations->name}}">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <!-- Start Time -->
                                <div class="mb-3">
                                    <label for="start_time" class="form-label"><b>Date</b></label>
                                    <input type="text" class="form-control readonly-label" id="start_time" name="start_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('dS F Y')
                                    }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- End Time -->
                                <div class="mb-3">
                                    <label for="end_time" class="form-label"><b>Time of Visit</b></label>
                                    <input type="text" class="form-control readonly-label" id="end_time" name="end_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                    }} - {{
                                        \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                    }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Next Planned Visit -->
                                <div class="mb-3">
                                    <label for="next_planned_visit" class="form-label"><b>Next Planned Visit</b></label>
                                    <input type="text" class="form-control readonly-label" id="next_planned_visit" name="next_planned_visit" value="{{$salescall->next_planned_visit}}">
                                </div>
                            </div>
                        </div>

                        <!-- Speciality (Drop-down) -->




                        <!-- Discussion Summary -->
                        <div class="mb-3">
                            <label for="discussion_summary" class="form-label"><b>Discussion Summary</b></label>
                            <textarea class="form-control readonly-label" id="discussion_summary" name="discussion_summary" rows="4">{{$salescall->discussion_summary}}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="discussion_summary" class="form-label"><b>Samples Given</b></label>
                                <hr>
                            </div>
                        </div>
                        @if (count($product_samples) > 1)
                            @foreach($product_samples as $product_sample)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>Sample</b></label>
                                            <input type="text" class="form-control readonly-label" id="last_name" name="last_name" value="{{$product_sample->product->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label"><b>Quantity Given</b></label>
                                            <input type="text" class="form-control readonly-label" value="{{$product_sample->quantity}}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    No samples given
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
