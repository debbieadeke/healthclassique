@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('salescalls.list-doctor')}}">Doctor Sales Calles</a></li>
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

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Doctor's Name</label>
                                        <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{$salescall->client->titles->name}}.  {{$salescall->client->first_name}} {{$salescall->client->last_name}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Location</label>
                                        <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="">

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <!-- Start Time -->
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Date</label>
                                    <input type="text" class="form-control readonly-label" id="start_time" name="start_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('dS F Y')
                                    }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- End Time -->
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Time of Visit</label>
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
                                    <label for="next_planned_visit" class="form-label">Next Planned Visit</label>
                                    <input type="text" class="form-control readonly-label" id="next_planned_visit" name="next_planned_visit" value="{{$salescall->next_planned_visit}}">
                                </div>
                            </div>
                        </div>

                        <!-- Speciality (Drop-down) -->




                        <!-- Discussion Summary -->
                        <div class="mb-3">
                            <label for="discussion_summary" class="form-label">Discussion Summary</label>
                            <textarea class="form-control readonly-label" id="discussion_summary" name="discussion_summary" rows="4">{{$salescall->discussion_summary}}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="discussion_summary" class="form-label">Samples Given</label>
                                <hr>
                            </div>
                        </div>
                        @if (count($product_samples) > 1)
                            @foreach($product_samples as $product_sample)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">Sample</label>
                                            <input type="text" class="form-control readonly-label" id="last_name" name="last_name" value="{{$product_sample->product->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Quantity Given</label>
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
