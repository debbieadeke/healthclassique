@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Representatives</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sale.index')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sale Representatives</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Sales Representatives </h4>
                </div>
                <div class="card-body">
                    <div class="card-block table-dash">
                        <div class="table-responsive">
                            <table  class="table mb-0 border-0 datatable custom-table table-striped">
                                <thead>
                                <tr style="font-size: 14px" >
                                    <th>No</th>
                                    <th>Employee Name</th>
                                    <th>Team</th>
                                    <th>view Facilities</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($salesReps as $userId => $saleRep)
                                    <tr style="font-size: 13px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $saleRep['employee_name'] }}</td>
                                        <td>{{ $saleRep['team_name'] }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('sale.repItems', ['userId' => $userId]) }}">
                                                        <i class="fa-solid fa-eye m-r-5" style="color:black; font-size: 12px;"></i>View
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
    </div>
@endsection
