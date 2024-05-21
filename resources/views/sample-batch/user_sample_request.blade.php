@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container-fluid">
            <div class="row">
                <div class="col-sm-7 col-6">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item" aria-current="page"> <a href="{{route('sample-batch.approve-sample-request')}}">Sample Requests</a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">Sample Request details</li>
                    </ul>
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
                                            <input type="number" min="0" id="approved_qty" class="form-control" size=1 name="approved_qty" value="{{$sample->quantity_approved}}" required>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <label for="notes" class="form-label"><b>Notes</b></label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" readonly>{{$sample->notes}}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="comment" class="form-label"><b>Comment</b></label>
                                            <textarea class="form-control" id="comment" name="comment" rows="3" >{{$sample->comments}}</textarea>
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
