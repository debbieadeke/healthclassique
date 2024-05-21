@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Facilities</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sale.index')}}">Dashboard</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('targets.admin-index')}}">Select Sales Rep</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Rep Facilities</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Sales Facilities </h4>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <div class="p-2">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px">
                                    <th>No</th>
                                    <th>Client Code</th>
                                    <th>Client Name</th>
                                    <th>Facility Type</th>
                                    <th>View Targets</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $index => $client)
                                    <tr style="font-size: 13px">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $client['code'] }}</td>
                                        <td>{{ $client['name'] }}</td>
                                        <td>{{ $client['facility_type'] }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ $client['type'] === 'pharmacy' ? route('targets.admin_pharmacies_targets', ['id' =>$client['id'], 'user_id'=>$user_id]) : route('targets.admin_facility_targets', ['id' =>$client['id'], 'user_id'=>$user_id]) }}">
                                                        <i class="fas fa-eye" style="color: deepskyblue; font-size: 18px;"></i> &nbsp;View Targets
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
