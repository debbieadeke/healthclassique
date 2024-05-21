@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Leave Days</h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">Users Leave Days</li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-semibold">Users Leave Days</h5>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                            <tr style="font-size: 14px">
                                <th>No</th>
                                <th>Full Name</th>
                                <th>Leave Days</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as  $user)
                                <tr style="font-size: 13px">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>
                                        @if ($user->yearlyLeaves->isNotEmpty())
                                            @foreach ($user->yearlyLeaves as $yearlyLeave)
                                                {{ $yearlyLeave->days_allocated }} (days)
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('leaves.assign_leave_days', ['id' => $user->id]) }}">
                                                    <i class="fa-solid fa-pen-clip m-r-5" style="color:black; font-size: 12px;"></i>Assign leave Days
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
