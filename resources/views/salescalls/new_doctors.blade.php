@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>New Doctor</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">New Doctor</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('salescalls.newDoctor') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="title" class="form-label"><b>Title</b></label>
                                            <select class="form-control" id="title" name="title">
                                                <option value="" disabled selected>Select Title</option>
                                                @foreach($titles as $title)
                                                    <option value="{{ $title->id }}">{{ $title->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="first_name" class="form-label"><b>First Name</b></label>
                                            <input type="text" id="first_name" name="first_name" placeholder="First Name" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="last_name" class="form-label"><b>Last Name</b></label>
                                            <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="location" class="form-label"><b>Location</b></label>
                                            <select class="form-control" id="location" name="location">
                                                <option value="" disabled selected>Select a Location</option>
                                                @foreach($data['locations'] as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-4">
                                            <label for="category" class="form-label"><b>Category</b></label>
                                            <input type="text" class="form-control" id="category" placeholder="category" name="category">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="speciality" class="form-label"><b>Speciality</b></label>
                                            <select class="form-control" id="speciality" name="speciality">
                                                <option value="" disabled selected>Select a Speciality</option>
                                                @foreach($specialities as $speciality)
                                                    <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="clinics" class="form-label"><b>Preferred Clinics</b></label>
                                            <select class="form-control" id="clinics" name="clinics[]" multiple>
                                                @foreach($clinics as $clinic)
                                                    <option value="{{ $clinic['id'] }}">
                                                        {{ $clinic['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
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
                    <h5 class="card-title fw-semibold mb-4">My Doctors</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Full Name</th>
                                    <th>Location</th>
                                    <th>Preferred Clinics</th>
                                    <th>Speciality</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($doctors as $doctor)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td >{{ $doctor->title->name }}</td>
                                        <td >{{ $doctor['first_name'] }}  {{$doctor['last_name']}}</td>
                                        <td >{{ $doctor->location->name }}</td>
                                        <td>
                                            <div style="max-height: 50px; overflow-y: auto; background-color: white; border: 1px solid #ccc;">
                                            @if($doctor->clinics)
                                                <?php
                                                    $clinicIds = json_decode($doctor->clinics);
                                                    $clinics = \App\Models\Facility::whereIn('id', $clinicIds)->get();
                                                ?>
                                                @foreach($clinics as $clinic)
                                                        <div>{{ $clinic->name }}</div>
                                                @endforeach
                                            @endif
                                            </div>
                                        </td>
                                        <td >{{ $doctor->speciality->name }}</td>
                                        <td >{{ $doctor->category }}</td>
                                        <td>
                                                <?php
                                                $statusClass = $doctor['status'] === 'Pending' ? 'warning' : ($doctor['status'] === 'Rejected' ? 'danger' : ($doctor['status'] === 'Approved' ? 'success' : ''));
                                                ?>
                                            <span class="badge bg-{{ $statusClass }}">{{ $doctor['status'] }}</span>
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
