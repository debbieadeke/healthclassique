@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Facilities</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sale.index')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sale.report')}}">Sale Representative</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales items Report<i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Sales Facilities </h4>
                </div>
                <div class="card-body">
                    <div class="card-block table-dash">
                        <div class="table-responsive">
                            <table  class="table mb-0 border-0 datatable custom-table table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Facilities Code</th>
                                    <th>Facilities Name</th>
                                    <th>Target</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($salesFacilities as  $salesFacilitie)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $salesFacilitie->customer_code }}</td>
                                        <td>{{ $salesFacilitie->customer_name }}</td>
                                        <td>{{ number_format($salesFacilitie->target) }}</td>
                                        <td>{{ number_format($salesFacilitie->total_quantity) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
