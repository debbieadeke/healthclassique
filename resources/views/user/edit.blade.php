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
                                    Edit User
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
                <form action="{{route('users.update', [$user->id])}}" method="post" id="myForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="{{$user->first_name}}">
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" placeholder="Last Name" name="last_name" value="{{$user->last_name}}">
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@healthclassique.com" autocomplete="off" value="{{$user->email}}">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="" disabled>Select a Role</option>
                                @foreach($data['roles'] as $role)
                                    <option value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(in_array('user', $userRoles))
                            <div class="col-md-4" id="teamDropdownContainer">
                                <label for="team" class="form-label">Team</label>
                                <select class="form-control" id="team" name="team">
                                    <option value="" disabled>Select a Team</option>
                                    @foreach($data['teams'] as $team)
                                        <option value="{{ $team->id }}" {{ $user->team_id == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ old('status', $data['user']->active_status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $data['user']->active_status) == 0 ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-4" style="padding-top: 40px;">
                                <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                                <label class="form-check-label" for="change_password">
                                    Change Password
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ isset($data['user']) && $data['user']->password ? '********' : '' }}" value="" autocomplete="off" {{ isset($data['user']) && $data['user']->password ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="row">
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
    <script>
        document.getElementById('role').addEventListener('change', function() {
            var selectedRole = this.value;
            var teamDropdownContainer = document.getElementById('teamDropdownContainer');
            teamDropdownContainer.style.display = (selectedRole === 'sale') ? 'block' : 'none';
        });
        document.getElementById('change_password').addEventListener('change', function () {
            var passwordInput = document.getElementById('password');
            passwordInput.disabled = !this.checked;
        });
    </script>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
@stop
