@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Edit Sample Request</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Sample Request</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Sample Inventory</h5>
                    <button onclick="location.href='{{ route('sample-batch.create-inventory') }}'" class="btn btn-success float-end" aria-disabled="true">
                        <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                        Create
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-dash col-12">
                        <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>User <br> Name</th>
                                <th>Product <br> Name</th>
                                <th>Qty <br> Req</th>
                                <th>Date</th>
                                <th>Manage Samples</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($samples as $sample)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td> {{ ucfirst(substr($sample->user->first_name, 0, 1)) }}.{{ $sample->user->last_name }}</td>
                                    <td>{{ $sample->product->name }}%</td>
                                    <td >{{ $sample->quantity_requested }}</td>
                                    <td >{{ $sample->created_at}}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <span class="dropdown-item">
                                                <form action="{{ route('sample-batch.destroySampleRequest', ['id' => $sample->id]) }}" method="POST" id="deleteForm{{$sample->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $sample->id }}"/>
                                                    <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Sample Request');" style="padding: 0;">
                                                        <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Sample
                                                    </button>
                                                </form>
                                            </span>
                                                <a class="dropdown-item" href="{{route('sample-batch.editSampleRequest',['id' =>  $sample->id, 'userId'=>$sample->user->id])}}">
                                                    <i class="fas fa-plus" style="color:green; font-size: 14px;"></i>&nbsp;Edit Sample</a>
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
