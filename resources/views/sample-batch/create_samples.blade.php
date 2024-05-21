@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Create Samples Inventory</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sample-batch.sample-inventory')}}">Sample Inventory</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Samples Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="sampleInventory" method="post" action="{{ route('sample-batch.store-inventory') }}">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="product_id" class="form-label"><b>Select Sample Product</b></label>
                                        <select class="form-control select2" style="width: 100%; height: 40px" id="product_id" name="product_id"  onchange="updateProductCode()" required>
                                            <option value="" selected>Select Product</option>
                                            @foreach($products as $product)
                                                <option class="form-control" value="{{ $product->id }}" data-product-code="{{ $product->code }}" data-product-name="{{ $product->name }}">
                                                    {{ $product->name . '  (' . $product->code . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="quantity" class="form-label"><b>Number of Items</b></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" step="1"  required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-4">
                                            <button id="submitGpsButton" type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Samples Inventory</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($samples as $sample)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $sample->product->name }}%</td>
                                    <td>{{ $sample->quantity }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <span class="dropdown-item">
                                                <form action="{{ route('sample-batch.destroy-inventory', ['id' => $sample->id]) }}" method="POST" id="deleteForm{{$sample->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $sample->id }}"/>
                                                    <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete Sample?');" style="padding: 0;">
                                                        <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 14px;"></i> Delete
                                                    </button>
                                                </form>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
