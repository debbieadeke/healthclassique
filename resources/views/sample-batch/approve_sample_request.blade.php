@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Sample Request</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page"> <a href="{{route('sample-batch.approve-sample-request')}}">Approve Sample Request</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Sample Request</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('sample-batch.approve_user_sample', ['id'=>$sample->id]) }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="product_id" class="form-label"><b>Product Name</b></label>
                                            <input type="text"  id="product_id" class="form-control"  name="product_id" value="{{$sample->product->name}}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="product_qty" class="form-label"><b>Quantity Requested</b></label>
                                            <input type="number" min="0" id="product_qty" class="form-control" size=1 name="product_qty" value="{{$sample->quantity_requested}}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="approved_qty" class="form-label"><b>Approved Quantity</b></label>
                                            <input type="number" min="0" id="approved_qty" class="form-control" size=1 name="approved_qty" value="" required>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <label for="notes" class="form-label"><b>Notes</b></label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" readonly>{{$sample->notes}}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="comment" class="form-label"><b>Comment</b>(Optional)</label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" ></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Approve</button>
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
