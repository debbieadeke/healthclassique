@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Delete Sales Records</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Deletes Sale</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Delete Sales</h5>
                </div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body">

                            <div class="form-body">
                                <div class="container">
                                    <form method="GET" action="{{route('sale.sales_record')}}">
                                        <div class="row">
                                            <label for="user_id" class="col-form-label col-12 col-md-2">Select Date Range</label>
                                            <div class="col-12 col-md-5">
                                                <div class="input-group">
                                                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{ $start_date }}">
                                                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{ $end_date }}">
                                                    <input type="hidden" name="filter" value="is_on">
                                                    <button class="btn btn-light-danger text-danger font-medium" type="submit">Go !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
