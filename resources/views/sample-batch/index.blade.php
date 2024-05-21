@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Draft Orders</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    List
                                </li>
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
                        <h5 class="card-title fw-semibold mt-2">{{$pagetitle}} | <a href="{{route('production-order.index')}}">Production Orders</a></h5>
                    </div>
                    <div class="col-4">


                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table text-nowrap mb-0 align-middle">
                        <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Order / Batch No</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Product</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Batch Quantity</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Batch Cost (Ksh)</h6>
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
                        @if (count($draft_orders) > 0)
                            @foreach($draft_orders as $draft_order)
                                <tr>
                                    <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ $draft_order->product->name }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ number_format($draft_order->production_quantity_target,0) }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{ number_format($draft_order->total_batch_cost,2) }}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <span class="fw-normal">{{$draft_order->created_at->format('d M Y')}}</span>
                                    </td>
                                    <td class="border-bottom-0">
                                        <a class="btn btn-primary m-1" href="{{ route('draft-order.show', ['draft-order' => $draft_order->id]) }}" role="button">Continue Preparing Order</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="border-bottom-0" colspan=7><h6 class="fw-semibold mb-0">No production orders </h6></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

@endsection

