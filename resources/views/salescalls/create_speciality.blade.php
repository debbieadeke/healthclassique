@extends('layouts.app-v2')
@section('content-v2')
    <div class="card card-default">
        <div class="card-header clearfix">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Create New Speciality</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Create New Speciality
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
    </div>
        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('salescalls.store_speciality')}}" method="post" id="myForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="form-label"><b>Speciality Name</b></label>
                            <input type="text" class="form-control" id="name" placeholder="Speciality" name="name"  required>
                        </div>
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Create</button>
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
@endsection
