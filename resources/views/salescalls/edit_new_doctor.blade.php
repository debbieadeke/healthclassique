@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Create New Doctor</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" href="{{route('salescalls.admin_new_doctor')}}"> New Doctor</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" >Create New Doctor</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{asset('assets/images/breadcrumb/ChatBc.png')}}" alt="" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{ route('salescalls.create_new_doctor') }}" method="post" id="myForm">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                    <div class="row pt-4">
                        <div class="col-md-2">
                            <label for="title" class="form-label"><b>Title</b></label>
                            <select class="form-control" id="title" name="title">
                                <option value="" disabled selected>Select Title</option>
                                @foreach($titles as $title)
                                    <option value="{{ $title->id }}" @if($title->id == $doctor->title->id) selected @endif>{{ $title->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="code" class="form-label"><b>Code</b></label>
                            <input type="text" class="form-control" id="code" placeholder="Code" name="code" required>
                        </div>
                        <div class="col-md-3">
                            <label for="first_name" class="form-label"><b>First Name</b></label>
                            <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="{{  $doctor->first_name }}">
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label"><b>Last Name</b></label>
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{  $doctor->last_name }}">
                        </div>
                    </div>
                    <div class="row pt-4" >
                        <div class="col-md-4">
                            <label for="category" class="form-label"><b>Category</b></label>
                            <input type="text" class="form-control" id="category" placeholder="category" name="category" value="{{  $doctor->category }}">
                        </div>
                        <div class="col-md-4">
                            <label for="location" class="form-label"><b>Location</b></label>
                            <select class="form-control" id="location" name="location">
                                <option value="" disabled>Select a Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" @if($location->id == $doctor->location->id) selected @endif>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="speciality" class="form-label"><b>Speciality</b></label>
                            <select class="form-control" id="speciality" name="speciality">
                                <option value="" disabled selected>Select a Speciality</option>
                                @foreach($specialities as $speciality)
                                    <option value="{{ $speciality->id }}" @if($speciality->id == $doctor->speciality->id) selected @endif>{{ $speciality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-6">
                            <label for="clinics" class="form-label"><b>Preferred Clinics</b></label>
                            <select class="form-control" id="clinics" name="clinics[]" multiple>
                                @foreach($clinics as $clinic)
                                        <?php
                                        $clinicIds = json_decode($doctor->clinics);
                                        ?>
                                    <option value="{{ $clinic['id'] }}" @if(in_array($clinic['id'], $clinicIds)) selected @endif>
                                        {{ $clinic['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="row pt-4">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Create</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
