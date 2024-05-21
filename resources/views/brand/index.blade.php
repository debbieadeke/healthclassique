@php(extract($data))
@extends('layouts.app',['pagetitle'=>$pagetitle])

@section('content')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Brands</h1>
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
                    <div class="col-9">
                        <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                    </div>
                    <div class="col-3">

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
                                <h6 class="fw-semibold mb-0">Name</h6>
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
                        @if (count($brands) > 0)
                            @foreach($brands as $brand)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $brand->name }}</span>
                                    </td>
                                    <td class="border-bottom-0">

                                    </td>
                                    <td class="border-bottom-0">

                                    </td>

                                    <td class="border-bottom-0">

                                    </td>
                                    <td class="border-bottom-0">

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No brands added</h6></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

@endsection

