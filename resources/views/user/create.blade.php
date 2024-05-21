@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">User</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Create User
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
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('users.store')}}" method="post" id="myForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@healthclassique.com" autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-4" id="roleDropdownContainer">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="" disabled selected>Select a Role</option>
                                @foreach($data['roles'] as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="teamDropdownContainer" style="display: none;">
                            <label for="team" class="form-label">Team</label>
                            <select class="form-control" id="team" name="team">
                                <option value="" disabled selected>Select a Team</option>
                                @foreach($data['teams'] as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="" autocomplete="off">
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Save</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">

                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Event handler for the role dropdown change
                $('#role').on('change', function () {
                    // Get the selected role
                    var selectedRole = $(this).val();

                    // Check if the selected role is the one that triggers the team dropdown
                    if (selectedRole === 'user') { // Change 'user' to the actual role that triggers the team dropdown
                        // Show the teamDropdownContainer
                        $('#teamDropdownContainer').show();
                    } else {
                        // Hide the teamDropdownContainer
                        $('#teamDropdownContainer').hide();
                    }
                });
            });
        </script>
    @endpush

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
@stop
