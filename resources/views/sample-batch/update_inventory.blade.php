@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>update Samples Inventory</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sample-batch.sample-inventory')}}">Sample Inventory</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Samples Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="sampleInventory" method="post" action="{{ route('sample-batch.stock-update-inventory', ['id' =>  $sample->id]) }}">
                            @csrf
                            <div class="form-body">
                                <input type="text" class="form-control" id="product_id" name="product_id" value="{{$sample->id}}" hidden>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="product" class="form-label"><b>Select Sample Product</b></label>
                                        <input type="text" class="form-control" id="product" name="product" value="{{$sample->product->name}}" readonly required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="quantity" class="form-label"><b>Quantity in Stock</b></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="{{$sample->quantity}}" readonly required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="stock" class="form-label"><b>Incoming Stock</b></label>
                                        <input type="number" class="form-control" id="stock" name="stock" step="1"  required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-4">
                                            <button id="submitGpsButton" type="submit" class="btn btn-success" name="action" value="item_submit">update</button>
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
