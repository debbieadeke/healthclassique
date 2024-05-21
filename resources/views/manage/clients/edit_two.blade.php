@php(extract($data))
@extends('layouts.app-v2')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@section('content-v2')
    <div class="card card-default">
        <div class="card-header clearfix">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Update Client</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Update Client
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('client-users.update-clients',['id' => $client->id])}}" method="post" id="myForm">
                    <div class="row pt-2">
                        <div class="col-md-4">
                            <label for="code" class="form-label">Client Code</label>
                            <input type="text" class="form-control" id="code" placeholder="Code" name="code" value="{{$client->code}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" placeholder="John" name="first_name" value="{{$client->first_name}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" placeholder="Doe" name="last_name" value="{{$client->last_name}}" required>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-4">
                            <label for="title" class="form-label">Title</label>
                            <select class="form-control" id="speciality" name="title">
                                <option value="" disabled selected>Select Title</option>
                                @foreach($data['title'] as $title)
                                    <option value="{{ $title->id }}" {{ $client->title_id == $title->id ? 'selected' : '' }}>{{ $title->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" placeholder=" Doctor" name="category" value="{{$client->category}}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="speciality" class="form-label">speciality</label>
                            <select class="form-control" id="speciality" name="speciality">
                                <option value="" disabled selected>Select Speciality</option>
                                @foreach($data['speciality'] as $speciality)
                                    <option value="{{ $speciality->id }}" {{ $client->speciality_id == $speciality->id ? 'selected' : '' }}>{{ $speciality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-4">
                            <label for="location" class="form-label">Location</label>
                            <select class="form-control" id="location" name="location">
                                <option value="" disabled selected>Select a Location</option>
                                @foreach($data['location'] as $location)
                                    <option value="{{ $location->id }}" {{ $client->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-3">
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">

                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        function handleOptionSelection(select) {
            var selectedIndex = select.selectedIndex;
            var selectedOption = select.options[selectedIndex];
            var code = selectedOption.getAttribute('data-code');
            var facilityType = selectedOption.getAttribute('data-facility-type');

            // Add hidden inputs to the form with the selected facility's code and facility type
            var form = document.getElementById('myForm');
            var codeInput = document.createElement('input');
            codeInput.setAttribute('type', 'hidden');
            codeInput.setAttribute('name', 'facility_code');
            codeInput.setAttribute('value', code);
            form.appendChild(codeInput);

            var facilityTypeInput = document.createElement('input');
            facilityTypeInput.setAttribute('type', 'hidden');
            facilityTypeInput.setAttribute('name', 'facility_type');
            facilityTypeInput.setAttribute('value', facilityType);
            form.appendChild(facilityTypeInput);
        }
    </script>
@endsection
