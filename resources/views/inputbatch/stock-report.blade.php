@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">{{$pagetitle}}</h4>
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
                        <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
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
                            <th style="font-size: 12px">Item Code</th>
                            <th style="font-size: 12px">Batch No</th>
                            <th style="font-size: 12px">Supplier</th>
                            <th style="font-size: 12px">Price</th>
                            <th style="font-size: 12px">Purchase Date</th>
                            <th style="font-size: 12px">Quantity</th>
                            <th style="font-size: 12px">Stock <br> Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($input_batches) > 0)
                            @foreach($input_batches as $input_batch)
                                <tr style="font-size: 12px">
                                    <td>{{$loop->iteration}}</td>
                                    <td style="font-size: 12px"  >{{ $input_batch->input->code ?? '' }}</td>
                                    <td style="font-size: 12px"  >{{ $input_batch->batch_number ?? '' }}</td>
                                    <td style="font-size: 12px"  >{{ $input_batch->supplier->name ?? '' }}</td>
                                    <td style="font-size: 12px"  >{{ number_format($input_batch->buying_price,2) ?? '' }}</td>
                                    <td style="font-size: 12px"  >{{ $input_batch->date_supplied->format('d M Y') ?? '' }}</td>
                                    <td style="font-size: 12px"  >{{ number_format($input_batch->quantity_purchased ?? '') }}</td>
                                    <td style="font-size: 12px"  >{{ number_format($input_batch->quantity_remaining ?? '') }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

{{--                <div class="">--}}
{{--                    <div class="">--}}
{{--                        <table class="display table-striped" id="myDataTable">--}}
{{--                            <thead class="text-dark fs-4">--}}


{{--                            <tr>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">No</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Input</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Code</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Supplier</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Batch No</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Price</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Date Supplied</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Quantity</h6>--}}
{{--                                </th>--}}
{{--                                <th class="border-bottom-0">--}}
{{--                                    <h6 class="fw-semibold mb-0">Remaining</h6>--}}
{{--                                </th>--}}


{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                @if (count($input_batches) > 0)--}}
{{--                                    @foreach($input_batches as $input_batch)--}}
{{--                                        <tr>--}}
{{--                                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0"> {{$loop->iteration}}</h6></td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ $input_batch->input->name ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ $input_batch->input->code ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ $input_batch->supplier->name ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ $input_batch->batch_number ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ number_format($input_batch->buying_price,2) ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ $input_batch->date_supplied->format('d M Y') ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ number_format($input_batch->quantity_purchased) ?? '' }}</span>--}}
{{--                                            </td>--}}
{{--                                            <td class="border-bottom-0">--}}
{{--                                                <span class="fw-normal">{{ number_format($input_batch->quantity_remaining) ?? '' }}</span>--}}
{{--                                            </td>--}}


{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <tr>--}}
{{--                                        <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No inputs received </h6></td>--}}
{{--                                    </tr>--}}
{{--                                 @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
        </div>
    </div>

@endsection

