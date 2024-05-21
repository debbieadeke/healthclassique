@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Quarterly Sale report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quarterly Sale report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold"> Quarterly Sales Report</h5>
                </div>
                <div class="card-body">

                    <div class="card">
                        <div class="card-body">

                            <div class="form-body">
                                <div class="container">
                                </div>
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h4 class="card-title d-inline-block">Sales Representatives</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-dash">
                                            <div class="table-responsive">
                                                <table  class="table table-responsive mb-0 border-0 datatable custom-table table-striped">
                                                    <thead>
                                                    <tr style="font-size: 14px">
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                        <th>Team</th>
                                                        <th>view  Facilities</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($salesReps as $userId => $saleRep)
                                                        <tr style="font-size: 13px">
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $saleRep['employee_name'] }}</td>
                                                            <td>{{ $saleRep['team_name'] }}</td>
                                                            <td class="text-center">
                                                                <div class="dropdown dropdown-action">
                                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="{{ route('sale.quarterlyRepItems', ['userId' => $userId]) }}"><i class="fa-solid fa-eye m-r-5" style="color:deepskyblue; font-size: 18px;"></i>View</a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
