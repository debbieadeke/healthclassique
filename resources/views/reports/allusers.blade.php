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
			<div class="card-header">
				<div class="row">
					<div class="col-7">
						<h5 class="card-title fw-semibold mt-2">Sales calls for All Users on  {{\Carbon\Carbon::parse($filter_date)->format('d M Y') }}</h5>
					</div>
					<div class="col-5">
					<form method="get" action="{{route('salescalls.list')}}">
					@csrf
						<div class="input-group">
							<input type="date" class="form-control" name="filter_date" value="{{$filter_date}}">
							<button class="btn btn-light-info text-info font-medium" type="submit">Go</button>
						</div>
					</form>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="row">
					<div class="col-2"><strong>Coverage:</strong>
					</div>
					<div class="col-1">{{$coverage}}
					</div>
                    <div class="col-2"><strong>Book Orders:</strong>
                    </div>
                    <div class="col-1">{{$book_orders}}
                    </div>
                    <div class="col-2"><strong>Pharmacy Audits:</strong>
                    </div>
                    <div class="col-1">{{$pharmacy_audits}}
                    </div>
                    <div class="col-2"><strong>Pharmacy Audits:</strong>
                    </div>
                    <div class="col-1">{{$pharmacy_audits}}
                    </div>
				</div>
			</div>
		</div>
    </div>

		<!-- Start Clinic Report -->
        <div class="card">
                <div class="card-body">
                    <div class="card-header p-0 m-0">
                    <div class="row">
                        <div class="col-9">
                            <h6 class="fw-semibold mt-2">Clinic Sales Calls List</h6>
                        </div>
                        <div class="col-3">

                        </div>
                    </div>
                    </div>
                    <div class="card-body p-0">

                <div class="table-responsive">
                  <table class="table table-sm text-nowrap mb-0 align-middle">
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
                        @if (count($salescalls1) > 0)
                        @foreach($salescalls1 as $salescall)
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{$salescall->facility->name}}</h6>
                                    @if (isset($salescall->facility->location))
                                    <span class="fw-normal">{{$salescall->facility->location->name}}</span>
                                    @endif
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
                                    @if($salescall->double_call == "No")
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
                                    <a class="btn btn-primary m-1" href="{{ route('salescalls.show-hospital', ['salescall' => $salescall->id]) }}" role="button">View Sales Call</a>

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
		<!-- End Clinic Report -->

		<!-- Start Doctor Report -->
		<div class="card">
                <div class="card">
					<div class="card-header p-0">
                    <div class="row">
                        <div class="col-9">
                            <h6 class="fw-semibold mt-2">Doctor Sales Calls List</h6>
                        </div>
                        <div class="col-3">

                        </div>
                    </div>
                    </div>
                    <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-sm text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Doctor's Name</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Time of Sales Call</h6>
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
						@if (count($salescalls2) > 0)
                        @foreach($salescalls2 as $salescall)
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{$salescall->client->first_name}} {{$salescall->client->last_name}}</h6>
                                    <span class="fw-normal"></span>
                                </td>
                                <td class="border-bottom-0">
                                    <p class="mb-0 fw-normal">
									{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                    }} -
                                    {{
                                        \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                    }}</p>
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
                                    <a class="btn btn-primary m-1" href="{{ route('salescalls.show', ['salescall' => $salescall->id]) }}" role="button">View Sales Call</a>

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
		<!-- End Doctor Report -->

		<!-- Start Pharmacy Report -->
		<div class="card">
                <div class="card">
					<div class="card-header p-0">
                    <div class="row">
                        <div class="col-9">
                            <h6 class="fw-semibold mt-2">Pharmacy Sales Calls List</h6>
                        </div>
                        <div class="col-3">

                        </div>
                    </div>
                    </div>
                    <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-sm text-nowrap mb-0 align-middle">
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
                          <h6 class="fw-semibold mb-0">No of Pharmtechs Seen</h6>
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
						@if (count($salescalls3) > 0)
                        @foreach($salescalls3 as $salescall)
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-1">{{$salescall->pharmacy->name ?? '' }}</h6>
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
                                    <a class="btn btn-primary m-1" href="{{ route('salescalls.show-pharmacy', ['salescall' => $salescall->id]) }}" role="button">View Sales Call</a>

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
		<!-- End Pharmacy Report -->

    </div>
@endsection
@section('chart-scripts')
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    <script src="{{asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
@endsection
