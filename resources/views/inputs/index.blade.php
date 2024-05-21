@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Inputs</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item" aria-current="page">Inputs</li>
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

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{route('input.create')}}" class="btn btn-success float-right" role="button" aria-disabled="true">Create New Input</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-block">
                    <div class="table-dash p-2"></div>
                    <table  class="display" id="myDataTable">
                        <thead>
                        <tr>
                            <th style="font-size: 12px">No</th>
                            <th style="font-size: 12px">Input</th>
                            <th style="font-size: 12px">Internal Code</th>
                            <th style="font-size: 12px">Type</th>
                            <th style="font-size: 12px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($inputs) > 0)
                            @foreach($inputs as $input)
                                <tr style="font-size: 12px">
                                    <td>{{$loop->iteration}}</td>
                                    <td style="font-size: 12px" >{{ $input->name }}</td>
                                    <td style="font-size: 12px"  >{{ $input->code }}</td>
                                    <td style="font-size: 12px"  >{{ ucfirst($input->type) }}</td>
                                    <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('input.edit', ['id' => $input->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit User</a>
                                                <span class="dropdown-item">
                                                        <form action="{{ route('input.destroy', $input->id) }}" method="POST">
                                                        @csrf
                                                            @method('DELETE')

                                                        <div class="icon-container">
                                                            <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">
                                                                <i class="fa fa-trash" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Input
                                                            </button>
                                                        </div>
                                                    </form>
                                                    </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
{{--
                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">No</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Input</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Internal Code</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Type</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Edit</h6>
                            </th>
							<th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Delete</h6>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($inputs) > 0)
                            @foreach($inputs as $input)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $input->name }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $input->code }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ ucfirst($input->type) }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal"><a href="{{route('input.edit', ['id' => $input->id])}}">Edit</a></span>
                                    </td>
									<td class="border-bottom-0">
                                        <span class="fw-normal">
											<form action="{{ route('input.destroy', $input->id) }}" method="POST">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-danger">Delete Input</button>
											</form>
											</span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No inputs entered </h6></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div> --}}


            </div>
        </div>
    </div>

@endsection

