@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Issue Sample</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Issue Sample</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <form action="{{route('sample-batch.issue-samples')}}" method="post" id="myForm">
                    @csrf
                    <div class="card-header">
                        <h5 class="card-title fw-semibold">Issue Sample</h5>
                        <button  class="btn btn-success float-end" aria-disabled="true">
                            <i class="fas fa-check" style="color:white; font-size: 16px;"></i>
                            Issue
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-dash table-responsive col-12">
                            <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                                <thead>
                                <tr style="font-size: 14px">
                                    <th>No</th>
                                    <th>User <br> Name</th>
                                    <th>Product <br> Name</th>
                                    <th>Cmpny <br> Inv</th>
                                    <th>User <br> Inv</th>
                                    <th>Qty <br> Rqst</th>
                                    <th>Qty <br> Appd</th>
                                    <th>Issued <br> Qty</th>
                                    <th>View <br> More</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($samples as $sample)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td> {{ ucfirst(substr($sample->user->first_name, 0, 1)) }}.{{ $sample->user->last_name }}</td>
                                        <td >{{ $sample->product->name }}</td>
                                        <td>{{ $sample->sampleInventory->quantity }}</td>
                                        <td>{{ $sample->product_quantity ?? 0 }}</td>
                                        <td >{{ $sample->quantity_requested }}</td>
                                        <td >{{ $sample->quantity_approved }}</td>
                                        <td>
                                            <div class="col-md-4">
                                                <input type="number" min="0" class="form-control form-control-sm"  name="product_qty[]" value="{{ $sample->quantity_approved }}">
                                            </div>
                                            <input type="hidden" name="sample_id[]" value="{{$sample->id }}">
                                            <input type="hidden" name="produc_id[]" value="{{$sample->product->id }}">
                                            <input type="hidden" name="user_id[]" value="{{$sample->user->id }}">
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('sample-batch.issue-user_sample',['id' =>  $sample->id,'userId' =>$sample->user->id])}}">
                                                        <i class="fas fa-eye" style="color:green; font-size: 12px;"></i>&nbsp;View Sample</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        /* Hide the arrows */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
            min-width: 50px;
        }
    </style>
@endsection
