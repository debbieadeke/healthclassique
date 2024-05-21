@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sample Balance</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sample Balance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Sample Balance</h5>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Team</th>
                                <th>View Samples</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reps as  $rep)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $rep->first_name }} {{ $rep->last_name }}</td>
                                    <td>{{ $rep->team->name }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('sample-batch.user-sample-balance', ['id' => $rep->id]) }}">
                                                    <i class="fa-solid fa-eye m-r-5" style="color:deepskyblue; font-size: 18px;"></i>View
                                                </a>
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
