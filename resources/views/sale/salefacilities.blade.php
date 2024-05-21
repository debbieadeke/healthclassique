@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Facilities</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sale.index')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sale.salesrep')}}">Sale Representative</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales item Report<i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sale Facilities</li>
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
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($salesFacilities as $index => $salesFacility)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $salesFacility->customer_code }}</td>
                                        <td>{{ $salesFacility->customer_name }}</td>
                                        <td>{{ $salesFacility->total_quantity }}</td>
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
