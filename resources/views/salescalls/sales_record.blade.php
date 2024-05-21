@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Sales Records</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Records</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('salescalls.store-record-sale') }}" enctype="multipart/form-data" onsubmit="return validateForm()" >
                                @csrf
                                <input type="hidden" name="client_code" id="client_code" value="">
                                <input type="hidden" name="product_code" id="product_code" value="">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="client_name" class="form-label"><b>Select Customer</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="client_name" name="client_name"  onchange="updateClientCode()" required>
                                                <option value="" selected>Select Customer</option>
                                                @foreach($clients as $client)
                                                    <option class="form-control" value="{{ $client->name }}" data-client-code="{{ $client->code }}" data-client-name="{{ $client->name }}">
                                                        {{ $client->name . '  (' . $client->client_type . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="product_name" class="form-label"><b>Product Name</b></label>
                                            <select class="form-control select2" style="width: 100%; height: 40px" id="product_name" name="product_name"  onchange="updateProductCode()" required>
                                                <option value="" selected>Select Product</option>
                                                @foreach($products as $product)
                                                    <option class="form-control" value="{{ $product->name }}" data-product-code="{{ $product->code }}" data-product-name="{{ $product->name }}">
                                                        {{ $product->name . '  (' . $product->code . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <label for="product_qty" class="form-label"><b>Quantity</b></label>
                                            <input type="number" min="0" class="form-control" id="product_qty" size=1 name="product_qty" value="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date_sold" class="form-label"><b>Date Sold</b></label>
                                            <div class="input-group">
                                                <input type="date" id="date_sold" name="date_sold" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
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
                    <h5 class="card-title fw-semibold mb-4">My Sales Records</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Customer <br> Code</th>
                                    <th>Customer <br> Name</th>
                                    <th>Product <br> Code</th>
                                    <th>Product <br> Name</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Status</th>
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
                                        <td>{{ $sale->quantity }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>
                                                <?php
                                                $statusClass = $sale->status === 'Pending' ? 'warning' : (  $sale->status === 'Rejected' ? 'danger' : (  $sale->status === 'Approved' ? 'success' : ''));
                                                ?>
                                            <span class="badge bg-{{ $statusClass }}">{{   $sale->status }}</span>
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
<script>
    function updateClientCode() {
        // Get the selected option
        var selectedOption = document.getElementById('client_name').options[document.getElementById('client_name').selectedIndex];

        // Get the client Code from the selected option's data attribute
        var clientCode = selectedOption.dataset.clientCode;

        // Set the client type value to the hidden input field
        document.getElementById('client_code').value = clientCode;
    }
    function updateProductCode() {
        // Get the selected option
        var selectedOption = document.getElementById('product_name').options[document.getElementById('product_name').selectedIndex];

        // Get the client Code from the selected option's data attribute
        var productCode = selectedOption.dataset.productCode;

        // Set the client type value to the hidden input field
        document.getElementById('product_code').value = productCode;
    }
</script>
@endsection
