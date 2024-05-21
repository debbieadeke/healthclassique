@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Production Orders</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item" aria-current="page">List</li>
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
                        @if ($draft_production_orders == "Enabled")
                            <h5 class="card-title fw-semibold mt-2">{{$pagetitle}} | <a href="{{route('draft-order.index')}}">Draft Orders</a></h5>
                        @else
                            <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                        @endif
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{route('production-order.create')}}" class="btn btn-success float-right" role="button" aria-disabled="true">Create New Production Order</a>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-block">
                    <div class="table-dash p-2">
                        <table  class="display" id="myDataTable">
                            <thead>
                            <tr>
                                <th style="font-size: 12px">No</th>
                                <th style="font-size: 12px">Product</th>
                                <th style="font-size: 12px">Batch Quantity</th>
                                <th style="font-size: 12px">Batch Cost (Ksh)</th>
                                <th style="font-size: 12px">Date</th>
                                <th style="font-size: 12px">Status</th>
                                <th style="font-size: 12px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($production_orders) > 0)
                                @foreach($production_orders as $prod_order)
                                    <tr style="font-size: 12px">
                                        <td>{{$loop->iteration}}</td>
                                        <td style="font-size: 12px"  >{{ $input_batch->input->code ?? '' }}</td>
                                        <td style="font-size: 12px"  >{{ isset($prod_order->product) ? $prod_order->product->name : "" }}</td>
                                        <td style="font-size: 12px"  >{{ number_format($prod_order->production_quantity_target,0) }}</td>
                                        <td style="font-size: 12px"  >{{ number_format($prod_order->total_batch_cost,2) }}</td>
                                        <td style="font-size: 12px"  >{{$prod_order->created_at->format('d M Y')}}</td>
                                        <td style="font-size: 12px"  >{{ucfirst($prod_order->status)}}</td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('production-order.show', ['production_order' => $prod_order->id]) }}"><i class="fas fa-edit" style="color:black; font-size: 12px;"></i> View</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

{{--            <div class="">--}}
{{--                <div class="">--}}
{{--                    <table class="display table-striped" id="myDataTable">--}}
{{--                        <thead class="text-dark fs-4">--}}
{{--                        <tr>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">No</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">Product</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">Batch Quantity</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">Batch Cost (Ksh)</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">Date</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">Status</h6>--}}
{{--                            </th>--}}
{{--                            <th class="border-bottom-0">--}}
{{--                                <h6 class="fw-semibold mb-0">View</h6>--}}
{{--                            </th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @if (count($production_orders) > 0)--}}
{{--                            @foreach($production_orders as $prod_order)--}}
{{--                                <tr>--}}
{{--                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        --}}{{-- <span class="fw-normal">{{ $prod_order->productionsetting->product->name }}</span> --}}
{{--                                        <span class="fw-normal">{{ isset($prod_order->product) ? $prod_order->product->name : "" }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        --}}{{-- <span class="fw-normal">{{ number_format($prod_order->production_quantity_target,0) }}</span> --}}
{{--                                        <span class="fw-normal">{{ number_format($prod_order->production_quantity_target,0) }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ number_format($prod_order->total_batch_cost,2) }}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{$prod_order->created_at->format('d M Y')}}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <span class="fw-normal">{{ucfirst($prod_order->status)}}</span>--}}
{{--                                    </td>--}}
{{--                                    <td class="border-bottom-0">--}}
{{--                                        <a class="btn btn-primary m-1" href="{{ route('production-order.show', ['production_order' => $prod_order->id]) }}" role="button">View</a>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @else--}}
{{--                            <tr>--}}
{{--                                <td class="border-bottom-0" colspan=7><h6 class="fw-semibold mb-0">No production orders </h6></td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

