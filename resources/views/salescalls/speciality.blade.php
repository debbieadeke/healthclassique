@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Speciality</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Doctors Speciality</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Doctor Speciality</h5>
                    <a href="{{route('salescalls.create_speciality')}}" class="btn btn-success float-end" role="button" aria-disabled="true">
                        <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                        Create
                    </a>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>Speciality Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($specialities as  $speciality)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $speciality->name}}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('salescalls.edit_speciality',['id' => $speciality->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i> &nbsp; Edit Speciality</a>
                                                <span class="dropdown-item">
                                                       <form action="{{ route('salescalls.destroy_speciality', ['id' => $speciality->id]) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                           @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $speciality->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this speciality?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i>&nbsp; Delete Speciality
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
