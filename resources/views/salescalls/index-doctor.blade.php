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
                        <div class="col-6">
                            <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                        </div>
                        <div class="col-5">
                        <form method="get" action="{{route('salescalls.list-doctor')}}">
                        @csrf
                            <div class="input-group">
                                <input type="date" class="form-control" name="filter_start_date" value="{{$filter_start_date}}">
                                <input type="date" class="form-control" name="filter_end_date" value="{{$filter_end_date}}">
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
						@if (count($salescalls) > 0)
                        @foreach($salescalls as $salescall)
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
    </div>
@endsection
