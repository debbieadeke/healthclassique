@php(extract($data))
@extends('layouts.app-v2')

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h2>Manage Doctors for Clinics </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Doctors for Clinics</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header clearfix">
            <h4>Facility Name: &nbsp; {{$facility->name}}</h4>
        </div>
    </div>
    <div class="card p-4">
        <div class="card-body">
            <div class="container">
                <form action="{{ route('facility-doctors',['id'=>$facility->id]) }}" method="POST" id="doctorForm">
                    @csrf
                    @method('POST')
                    <table class="table table-dash table-responsive">
                        <thead>
                        <tr>
                            <th><b>Name</b></th>
                            <th><b>Select Doctors</b></th>
                            <th>Update</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td> {{$facility->name}}</td>
                            <td>
                                <select class="form-control" id="doctors" name="doctors[]" multiple>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor['id'] }}" {{ in_array($doctor['id'], $selectedDoctorIds) ? 'selected' : '' }}>
                                            {{ $doctor['first_name'] }} {{ $doctor['last_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="vertical-align: top"> <button type="submit" class="btn btn-primary">update</button></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>

@endsection
