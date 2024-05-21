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
                                    <form method="POST" action="{{route('sale.delete_filtered_records', ['start_date'=>$start_date,'end_date'=>$end_date])}}">
                                        <div class="row">
                                            <label for="user_id" class="col-form-label col-12 col-md-4">Selected Date Range</label>
                                            <div class="col-12 col-md-8">
                                                <div class="input-group">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="date" id="start_date" class="form-control" name="start_date" value="{{$start_date}}" disabled>
                                                    <input type="date" id="end_date" class="form-control" name="end_date" value="{{$end_date}}" disabled>
                                                    <input type="hidden" name="filter" value="is_on">
                                                    <button class="btn btn-danger  font-medium" type="submit"> Delete Filtered Items !</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card">
                                    <div class="card-header pb-0">
                                    </div>
                                    <div class="card-body">
                                        <div class="p-2">
                                            <div class="p-2">
                                                <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                                    <thead>
                                                    <tr style="font-size: 14px">
                                                        <th>No</th>
                                                        <th>Customer Code</th>
                                                        <th>Customer Name</th>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($sales as $sale)
                                                        <tr style="font-size: 13px">
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $sale->customer_code }}</td>
                                                            <td>{{ $sale->customer_name }}</td>
                                                            <td>{{ $sale->product_code }}</td>
                                                            <td>{{ $sale->product_name }}</td>
                                                            <td>{{ $sale->date }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
