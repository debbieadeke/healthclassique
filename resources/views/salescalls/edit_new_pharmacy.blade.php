@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Create New Clinic</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" href="{{route('targets.customers')}}"> New Clinic</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" >Create New Clinic</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{asset('assets/images/breadcrumb/ChatBc.png')}}" alt="" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{ route('salescalls.create_new_pharmacy_clinic') }}" method="post" id="myForm">
                    @csrf
                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">
                    <div class="row pt-4" >
                        <div class="col-md-4">
                            <label for="code" class="form-label"><b>Code</b></label>
                            <input type="text" class="form-control" id="code" placeholder="Code" name="code" value="" required>
                        </div>
                        <div class="col-md-4">
                            <label for="facility" class="form-label"><b>Facility Name</b></label>
                            <input type="text" class="form-control" id="facility" placeholder="Facility Name" name="facility" value="{{ $facility->facility_name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="location" class="form-label"><b>Location</b></label>
                            <select class="form-control" id="location" name="location">
                                <option value="" disabled>Select a Location</option>
                                @foreach($data['locations'] as $location)
                                    <option value="{{ $location->id }}" @if($location->id == $facility->location->id) selected @endif>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 10px;">
                        <div class="col-md-4">
                            <label for="type" class="form-label"><b>Type</b></label>
                            <select class="form-control" id="type" name="type">
                                <option value="" disabled>Select Facility type</option>
                                <option value="clinic" @if($facility->type == 'clinic') selected @endif>Clinic</option>
                                <option value="pharmacy" @if($facility->type == 'pharmacy') selected @endif>Pharmacy</option>
                            </select>
                        </div>
                    </div>
                        <div class="row pt-4" >
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Create</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                        </div>
                </form>
            </div>
        </div>
@endsection
