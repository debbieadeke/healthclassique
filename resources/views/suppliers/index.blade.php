@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h3 class="fw-semibold mb-8">Suppliers</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item active">Suppliers</li>
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
                        <a href="{{route('suppliers.create')}}" class="btn btn-success" role="button" aria-disabled="true">Create New Supplier</a>
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
                            <th style="font-size: 12px">Company Name</th>
                            <th style="font-size: 12px">Contact Person</th>
                            <th style="font-size: 12px">Phone</th>
                            <th style="font-size: 12px">Email</th>
                            <th style="font-size: 12px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($suppliers) > 0)
                            @foreach($suppliers as $supplier)

                                <tr style="font-size: 12px">
                                    <td>{{$loop->iteration}}</td>
                                    <td style="font-size: 12px" >
                                        {{ $supplier->name }}
                                    </td>
                                    <td style="font-size: 12px"  >{{ $supplier->contact_person_first_name }} {{ $supplier->contact_person_last_name  }}</td>
                                    <td style="font-size: 12px">
                                            <?php
                                            $phoneNumbers = explode('/', $supplier->phone_number);
                                            // Take the first two phone numbers, if available
                                            $displayPhoneNumbers = array_slice($phoneNumbers, 0, 2);
                                            echo implode(', ', $displayPhoneNumbers);
                                            ?>
                                    </td>
                                    <td style="font-size: 12px">{{ $supplier->email_address  }}</td>
{{--                                    <td style="font-size: 12px" >{{ $supplier->building }} - {{ $supplier->road }} - {{ $supplier->location }}</td>--}}
                                    <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('suppliers.show', ['id' => $supplier->id])}}"><i class="fas fa-eye-low-vision" style="color:black; font-size: 12px;"></i>View</a>
                                                <a class="dropdown-item" href="{{route('suppliers.edit', ['id' => $supplier->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 12px;"></i>Edit User</a>
                                                <span class="dropdown-item">
                                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                    <div class="icon-container">
                                                        <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">
                                                            <i class="fa fa-trash" aria-hidden="true" style="color:red; font-size: 12px;"></i> Delete Supplier
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
{{--                <table class="display" id="myDataTable">--}}
{{--                    <thead class="text-dark fs-4">--}}
{{--                    <tr>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">No</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Company Name</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Contact Person</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Phone</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Email</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Location</h6>--}}
{{--                        </th>--}}
{{--                        <th class="border-bottom-0">--}}
{{--                            <h6 class="fw-semibold mb-0">Actions</h6>--}}
{{--                        </th>--}}

{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                        @if (count($suppliers) > 0)--}}
{{--                            @foreach($suppliers as $supplier)--}}
{{--                                <tr>--}}
{{--                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0"> {{$loop->iteration}}</h6></td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ $supplier->name }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ $supplier->contact_person_first_name }} {{ $supplier->contact_person_last_name  }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ $supplier->phone_number }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ $supplier->email_address  }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ $supplier->building }} - {{ $supplier->road }} - {{ $supplier->location }}</span>--}}
{{--                                    </td>--}}

{{--                                    <td class="text-end">--}}
{{--                                        <div class="dropdown dropdown-action">--}}
{{--                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>--}}
{{--                                            <div class="dropdown-menu dropdown-menu-end">--}}
{{--                                                <a class="dropdown-item" href="{{route('suppliers.edit', ['id' => $supplier->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit User</a>--}}
{{--                                                <span class="dropdown-item">--}}
{{--                                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">--}}
{{--                                                        @csrf--}}
{{--                                                        @method('DELETE')--}}

{{--                                                    <div class="icon-container">--}}
{{--                                                        <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">--}}
{{--                                                            <i class="fa fa-trash" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Supplier--}}
{{--                                                        </button>--}}
{{--                                                    </div>--}}
{{--                                                </form>--}}
{{--                                                </span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}

{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @else--}}
{{--                            <tr>--}}
{{--                                <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No suppliers entered</h6></td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                    </tbody>--}}
{{--                </table>--}}
                {{-- end new design --}}

                {{-- <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">No</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Company Name</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Contact Person</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Phone</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Email</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Location</h6>
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
                        @if (count($suppliers) > 0)
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                    <td class="border-bottom-0">
                                        <div class="fw-normal text-wrap">{{ $supplier->name }}</div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $supplier->contact_person_first_name }} {{ $supplier->contact_person_last_name }} </span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="fw-normal text-wrap">{{ $supplier->phone_number }}</div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $supplier->email_address }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <div class="fw-normal text-wrap">{{ $supplier->building }} - {{ $supplier->road }} - {{ $supplier->location }}</div>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal"><a href="{{route('suppliers.edit', ['id' => $supplier->id])}}">Edit</a></span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">
											<form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
												@csrf
                                                @method('DELETE')
												<button type="submit" class="btn btn-danger">Delete Supplier</button>
											</form>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No suppliers entered </h6></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div> --}}


            </div>
        </div>
    </div>

@endsection

