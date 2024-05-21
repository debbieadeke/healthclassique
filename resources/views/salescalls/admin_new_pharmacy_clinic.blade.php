@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>New Clinic/Pharmacy</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Clinic/Pharmacy</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">New Facilities</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Facility Name</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Posted By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach ($facilities as $facility)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td >{{ $facility['facility_name'] }}</td>
                                        <td >{{ $facility->location->name }}</td>
                                        <td >{{ $facility['type'] }}</td>
                                        <td>
                                            <?php
                                                $statusClass = $facility['status'] === 'Pending' ? 'warning' : ($facility['status'] === 'Deleted' ? 'danger' : ($facility['status'] === 'Approved' ? 'success' : ''));
                                            ?>
                                            <span class="badge bg-{{ $statusClass }}">{{ $facility['status'] }}</span>
                                        </td>
                                        <td>{{ $facility->user->first_name }} {{ $facility->user->first_name }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a  class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('salescalls.edit_new_pharmacy',['id' => $facility->id])}}"><i class="fas fa-plus" style="color:green; font-size: 18px;"></i> &nbsp; Create Facility</a>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('salescalls.destroy_new_pharmacy', ['id' => $facility->id]) }}" method="POST" id="deleteForm{{$facility->id}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $facility->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Facility?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> &nbsp; Delete Facility
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
