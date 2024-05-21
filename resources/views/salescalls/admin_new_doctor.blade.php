@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>New Doctor</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">New Doctor</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">New Doctors</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Full Name</th>
                                    <th>Location</th>
                                    <th>Preferred Clinics</th>
                                    <th>Speciality</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Posted By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($doctors as $doctor)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td >{{ $doctor->title->name }}</td>
                                        <td >{{ $doctor['first_name'] }}  {{$doctor['last_name']}}</td>
                                        <td >{{ $doctor->location->name }}</td>
                                        <td>
                                            <div style="max-height: 50px; overflow-y: auto; background-color: white; border: 1px solid #ccc;">
                                                @if($doctor->clinics)
                                                        <?php
                                                        $clinicIds = json_decode($doctor->clinics);
                                                        $clinics = \App\Models\Facility::whereIn('id', $clinicIds)->get();
                                                        ?>
                                                    @foreach($clinics as $clinic)
                                                        <div>{{ $clinic->name }}</div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td >{{ $doctor->speciality->name }}</td>
                                        <td >{{ $doctor->category }}</td>
                                        <td>
                                            <?php
                                                $statusClass = $doctor['status'] === 'Pending' ? 'warning' : ($doctor['status'] === 'Deleted' ? 'danger' : ($doctor['status'] === 'Approved' ? 'success' : ''));
                                            ?>
                                            <span class="badge bg-{{ $statusClass }}">{{ $doctor['status'] }}</span>
                                        </td>
                                        <td>{{ $doctor->user->first_name }} {{ $doctor->user->first_name }}</td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('salescalls.edit_new_doctor',['id' => $doctor->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit New Doctor</a>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('salescalls.destroy_new_doctor', ['id' => $doctor->id]) }}" method="POST" id="deleteForm{{$doctor->id}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $doctor->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Client?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Doctor
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
