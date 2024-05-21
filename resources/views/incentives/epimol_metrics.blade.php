@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Incentives Metrics</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Incentives Metrics</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="epimolForm" method="post" action="{{ route('incentive.store-epimol-metrics') }}" enctype="multipart/form-data"  required>
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="percent" class="form-label"><b>Percentage</b></label>
                                        <input type="number" class="form-control" id="percent" name="percent" placeholder="%" required>
                                    </div>
                                   <div class="col-md-4">
                                        <label for="kpis" class="form-label"><b>KPI's Value</b></label>
                                        <input type="number" class="form-control" id="kpis" name="kpis" step="0.01"  required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="tier1" class="form-label"><b>Tier 1 value</b></label>
                                        <input type="number" class="form-control" id="tier1" name="tier1" step="0.01" required>
                                    </div>
                                </div>
                                <div class="row pt-2">

                                    <div class="col-md-4">
                                        <label for="tier2" class="form-label"><b>Tier 2 value</b></label>
                                        <input type="number" class="form-control" id="tier2" name="tier2"  step="0.01"  required>
                                    </div> <div class="col-md-4">
                                        <label for="tier3" class="form-label"><b>Tier 3 value</b></label>
                                        <input type="number" class="form-control" id="tier3" name="tier3" step="0.01" required>
                                    </div> <div class="col-md-4">
                                        <label for="total_individual" class="form-label"><b>Total Individual Value</b></label>
                                        <input type="number" class="form-control" id="total_individual" name="total_individual" step="0.01"  required>
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
                <h5 class="card-title fw-semibold mb-4">Incentives Metrics</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Achievement</th>
                                <th>KPI's</th>
                                <th>Tier 1</th>
                                <th>Tier 2</th>
                                <th>Tier 3</th>
                                <th>Total Individual</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($epimolMetrics  as $epimol)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $epimol->percentage }}%</td>
                                    <td>{{ $epimol->kPIs }}</td>
                                    <td>{{ $epimol->tier_1 }}</td>
                                    <td>{{ $epimol->tier_2 }}</td>
                                    <td>{{ $epimol->tier_3 }}</td>
                                    <td>{{ $epimol->total_individual }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('incentive.edit-epimol-metrics',['id' =>  $epimol->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit</a>
                                                <span class="dropdown-item">
                                                <form action="{{ route('incentive.destroy-epimol-metrics', ['id' => $epimol->id]) }}" method="POST" id="deleteForm{{$epimol->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $epimol->id }}"/>
                                                    <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Epimol Metrics?');" style="padding: 0;">
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
