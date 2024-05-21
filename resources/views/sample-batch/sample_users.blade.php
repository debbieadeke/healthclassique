@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-7 col-6">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                <li class="breadcrumb-item active">Sample Report</li>
            </ul>
        </div>
    </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Sample Report</h5>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>Full Name</th>
                                <th>View Sample</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reps as  $rep)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $rep->first_name }} {{ $rep->last_name }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('sample-batch.edit_user_inventory', ['id' => $rep->id]) }}">
                                                    <i class="fa-solid fa-pen-to-square m-r-5" style="color:black; font-size: 12px;"></i>Edit Inventory
                                                </a>
                                                <a class="dropdown-item" href="{{ route('sample-batch.view_user_inventory', ['id' => $rep->id]) }}">
                                                    <i class="fa-solid fa-eye m-r-5" style="color:black; font-size: 12px;"></i>View Inventory
                                                </a>
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
