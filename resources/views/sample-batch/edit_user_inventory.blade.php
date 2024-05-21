@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Sample Inventory</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sample Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Sample Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="table-dash col-12">
                        <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Current <br> Inventory</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($samples as $sample)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $sample->product->name }}</td>
                                    <td>{{ $sample->quantity }}</td>
                                    <td>
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{route('sample-batch.update_user_inventory',['id' =>  $sample->id])}}">
                                                <i class="fas fa-edit" style="color:black; font-size: 12px;"></i>&nbsp;Edit Inventory</a>
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
