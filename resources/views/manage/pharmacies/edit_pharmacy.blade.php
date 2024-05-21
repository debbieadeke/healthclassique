@php(extract($data))
@extends('layouts.app-v2')

@section('content-v2')

    <div class="card card-default">

        <div class="card-header clearfix">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Edit Pharmacy</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Edit Pharmacy
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('pharmacy.update', [$pharmacy->id])}}" method="post" id="myForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="code" class="form-label">Pharmacy Code</label>
                            <input type="text" class="form-control" id="code" placeholder="#Code" name="code" value="{{$pharmacy->code}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Pharmacy Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Pharmacy Name" name="name" value="{{$pharmacy->name}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="location" class="form-label">Location</label>
                            <select class="form-control" id="location" name="location">
                                <option value="" disabled>Select a Location</option>
                                @foreach($data['location'] as $location)
                                    <option value="{{ $location->id }}" {{ $pharmacy->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Update</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                        </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>

@endsection
