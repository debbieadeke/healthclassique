@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
<div class="container-fluid">
        <div class="row">
            <div class="col-sm-7 col-6">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Sample Request</li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('sample-batch.store_new_sample') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="product_id" class="form-label"><b>Product Name</b></label>
                                        <select class="form-control" id="product_id" name="product_id">
                                            <option value="" disabled selected>Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->product->id }}">{{ $product->product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="product_qty" class="form-label"><b>Quantity</b></label>
                                        <input type="number" min="0" class="form-control" size=1 name="product_qty" value="0">
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-md-6">
                                        <label for="notes" class="form-label"><b>Notes</b>(Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" ></textarea>
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
                <h5 class="card-title fw-semibold mb-4">My Samples Requests</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Requested <br> Qty</th>
                                <th>Approved <br> Qty</th>
                                <th>Issued <br> Qty</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>view <br> More</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($samples as $sample)
                                <tr style="font-size: 14px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td >{{ $sample->product->name }}</td>
                                    <td >{{ $sample->quantity_requested }}</td>
                                    <td >{{ $sample->quantity_approved }}</td>
                                    <td >{{ $sample->quantity_issued }}</td>
                                    <td>{{ $sample->comments ? 'Yes' : 'N/A' }}</td>
                                    <td>
                                        @if ($sample->approved_by < 1)
                                            <span class="circle bg-warning"></span> <!-- Yellow circle for Pending -->
                                        @elseif ($sample->approved_by > 0 && $sample->issued_by < 1)
                                            <span class="circle bg-success"></span> <!-- Green circle for Approved -->
                                        @else
                                            <span class="circle bg-info"></span> <!-- Blue circle for Issued -->
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('sample-batch.view-user-sample-request',['id' =>  $sample->id])}}">
                                                    <i class="fas fa-eye" style="color:green; font-size: 12px;"></i>&nbsp;View Sample</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <td></td>
                            <td colspan="2"></td>
                            <td colspan="2"></td>
                            <td colspan="1"> <span class="circle bg-warning"></span> Pending</td>
                            <td colspan="1"> <span class="circle bg-success"></span>Approved</td>
                            <td colspan="1"> <span class="circle bg-info"></span>Issued</td>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
<style>
    .circle {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px; /* Adjust margin as needed */
    }
</style>
@endsection
