@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Issue Sample</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page"> <a href="{{route('sample-batch.edit-sample-request')}}">Edit Samples Request</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Sample</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('sample-batch.updateSampleRequest', ['id'=>$sample->id]) }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <input type="text"  id="userId" class="form-control"  name="userId" value="{{$userId}}" hidden>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="product_id" class="form-label"><b>Product Id</b></label>
                                            <input type="text"  id="product_id" class="form-control"  name="product_id" value="{{$sample->product->id}}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="product_qty" class="form-label"><b>Quantity Requested</b></label>
                                            <input type="number" min="0" id="qty_rqt" class="form-control" size=1 name="qty_rqt" value="{{$sample->quantity_requested}}" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="product_qty" class="form-label"><b>Quantity Approved</b></label>
                                            <input type="number" min="0" id="qty_app" class="form-control" size=1 name="qty_app" value="{{$sample->quantity_approved}}" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="issued_qty" class="form-label"><b>Quantity Issued</b></label>
                                            <input type="number" min="0" id="qty_issued" class="form-control" size=1 name="qty_issued" value="{{$sample->quantity_approved}}" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="issued_qty" class="form-label"><b>Issued by</b></label>
                                            <input type="number" min="0" id="issued_by" class="form-control" size=1 name="issued_by" value="{{$sample->issued_by}}" >
                                        </div>
                                        <div class="col-md-4">
                                            <label for="issued_qty" class="form-label"><b>Approved by</b></label>
                                            <input type="number" min="0" id="app_by" class="form-control" size=1 name="app_by" value="{{$sample->approved_by}}" >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
