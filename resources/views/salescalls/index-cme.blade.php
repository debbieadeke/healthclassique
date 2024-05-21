@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
                <div class="card">
					<div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                        </div>
                        <div class="col-3">
                        <form method="get" action="{{route('salescalls.list-pharmacy')}}">
                        @csrf
                            <div class="input-group">
                                <input type="date" class="form-control" name="filter_date" value="{{$filter_date}}">
                                <button class="btn btn-light-info text-info font-medium" type="submit">Go!</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                <div class="table-responsive">
                  <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Client</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Time of Sales Call</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">No of Customers Seen</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Recorded on Site</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">View</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
						@if (count($salescalls) > 0)
                        @foreach($salescalls as $salescall)
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{$salescall->client->first_name}} {{$salescall->client->last_name}}</h6>
                                    <span class="fw-normal">{{$salescall->client_type}}</span>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
									{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                    }} -
                                    {{
                                        \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                    }}
									</p>
                                </td>
                                  <td class="border-bottom-0">
                                        <h6 class="fw-normal mb-1">{{count($salescall->salescalldetails)}}</h6>
                                        <span class="fw-normal"></span>
                                    </td>


                                <td class="border-bottom-0">
                                    @if($salescall->double_call == "Yes")
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary rounded-3 fw-semibold">Yes</span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success rounded-3 fw-semibold">Yes</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    <a class="btn btn-primary m-1" href="{{ route('salescalls.show-cme', ['salescall' => $salescall->id]) }}" role="button">View Sales Call</a>

                                </td>
                            </tr>
                        @endforeach
						@else
                            <tr>
                                <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No sales calls on selected date</h6></td>
                            </tr>
                        @endif
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
        <div class="card">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5 class="card-title fw-semibold mt-2">Clinic CME</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-dash p-2">
                        <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1"
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Client</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Time of Sales Call</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No of Customers Seen</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Recorded on Site</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">View</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($clinics) > 0)
                                @foreach($clinics as $clinic)
                                    <tr>
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{$clinic->client_type}}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{$clinic->facility->name}} </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{
                                                    \Carbon\Carbon::parse($clinic->start_time)->format('g:ia')
                                                }} -
                                                {{
                                                    \Carbon\Carbon::parse($clinic->end_time)->format('g:ia')
                                                }}
                                            </p>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-normal mb-1">{{count($clinic->salescalldetails)}}</h6>
                                            <span class="fw-normal"></span>
                                        </td>
                                        <td class="border-bottom-0">
                                            @if($clinic->double_call == "Yes")
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-secondary rounded-3 fw-semibold">Yes</span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success rounded-3 fw-semibold">Yes</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="border-bottom-0">
                                            <a class="btn btn-primary m-1" href="{{ route('salescalls.show-cme', ['salescall' => $clinic->id]) }}" role="button">View Sales Call</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No sales calls on selected date</h6></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5 class="card-title fw-semibold mt-2">Pharmacy CME</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-dash">
                        <table class="table mb-0 border-0 datatable custom-table table-striped" data-page-length="-1">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Customer <br> Type</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Client</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Time of Sales Call</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No of Customers Seen</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Date</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">View</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($pharmacies) > 0)
                                @foreach($pharmacies as $pharmacy)
                                    <tr>
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{$pharmacy->pharmacy->name}} </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-semibold mb-1">{{$pharmacy->client_type}} </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0 fw-normal">
                                                {{
                                                    \Carbon\Carbon::parse($pharmacy->start_time)->format('g:ia')
                                                }} -
                                                {{
                                                    \Carbon\Carbon::parse($pharmacy->end_time)->format('g:ia')
                                                }}
                                            </h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="fw-normal mb-0">{{count($pharmacy->salescalldetails)}}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                                <?php
                                                // Convert the created_at date to a Carbon instance
                                                $createdAt = \Carbon\Carbon::parse($pharmacy->created_at);

                                                // Get the day of the month with ordinal suffix
                                                $dayWithSuffix = $createdAt->format('jS');

                                                // Format the date as "2nd March"
                                                $formattedDate = $dayWithSuffix . ' ' . $createdAt->format('F');
                                                ?>

                                            <h6 class="fw-normal mb-1">{{ $formattedDate }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('salescalls.show-pharmacy', ['salescall' => $pharmacy->id]) }}" ><i class="fas fa-eye" style="color:slategray; font-size: 13px;"></i>View Sales Call</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No sales calls on selected date</h6></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
@endsection
