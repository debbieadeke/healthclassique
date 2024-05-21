@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>New Clinic/Pharmacy</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">New Clinic/Pharmacy</li>
                        </ol>
                    </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('salescalls.new_facility') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                            @csrf
                            <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="facility_name" class="form-label"><b>Facility Name</b></label>
                                    <input type="text" id="facility_name" name="facility_name" placeholder="Facility Name" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="location" class="form-label"><b>Location</b></label>
                                    <select class="form-control" id="location" name="location">
                                        <option value="" disabled selected>Select a Location</option>
                                        @foreach($data['locations'] as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label"><b>Type</b></label>
                                   <select class="form-control" id="type" name="type">
                                       <option value="" disabled selected>Select Facility type</option>
                                       <option value="clinic" >Clinic</option>
                                       <option value="pharmacy" >Pharmacy</option>
                                   </select>
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
                <h5 class="card-title fw-semibold mb-4">My Facilities</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px" class="text-center">
                                <th>No</th>
                                <th>Facility Name</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($facilities as $facility)
                                <tr style="font-size: 14px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td >{{ $facility['facility_name'] }}</td>
                                    <td >{{ $facility->location->name }}</td>
                                    <td >{{ $facility['type'] }}</td>
                                    <td>
                                            <?php
                                            $statusClass = $facility['status'] === 'Pending' ? 'warning' : ($facility['status'] === 'Rejected' ? 'danger' : ($facility['status'] === 'Approved' ? 'success' : ''));
                                            ?>
                                        <span class="badge bg-{{ $statusClass }}">{{ $facility['status'] }}</span>
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
