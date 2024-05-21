@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Clinic Sale Target</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Select a Clinic</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Set Targets for My Customers <b></b></h5>
                <div class="card-body px-4 py-3">
                    <form action="{{route('targets.customer_targets') }}" method="post" id="myForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="customer" class="form-label">Select Customer</label>
                                <select class="form-control select2" style="width: 100%; height: 40px" id="customer" name="customer"  required>
                                    <option value="" selected>Select Customer</option>
                                    @foreach ($data as $client)
                                        <option  class="form-control" value="{{ $client->id }}">{{ $client->name }} &nbsp; <b>({{ $client->facility_type }} )</b></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="row" style="padding-top: 20px;">
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
