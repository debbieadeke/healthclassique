@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sample Request Approval</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sample Request</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sample Request Approval</h5>
                <div class="card-body px-4 py-3">
                    <form action="{{route('sample-batch.user_approve') }}" method="post" id="myForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="user" class="form-label">Select User</label>
                                <select class="form-select" id="user" name="user">
                                    <option value="" disabled selected>Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="row" style="padding-top: 20px;">
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
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
    </div>

@endsection
