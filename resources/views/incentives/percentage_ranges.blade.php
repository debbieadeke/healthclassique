@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Percentage Ranges</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Percentage Ranges</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="gpsForm" method="post" action="{{ route('incentive.store-percentage-ranges') }}" enctype="multipart/form-data" onsubmit="return validateForm()" onchange="updateClientType()" required>
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="percent" class="form-label"><b>Percentage</b></label>
                                        <input type="number" class="form-control" id="percent" name="percent" placeholder="%" required>
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
                <h5 class="card-title fw-semibold mb-4">Percentage Ranges</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Client Name</th>
                                <th>View More</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($percentages as $percentage)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $percentage->percentage_range }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <span class="dropdown-item">
                                                <form action="{{ route('incentive.destroy-percentage-ranges', ['id' => $percentage->id]) }}" method="POST" id="deleteForm{{$percentage->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="id" value="{{ $percentage->id }}"/>
                                                    <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Pecentage range?');" style="padding: 0;">
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
