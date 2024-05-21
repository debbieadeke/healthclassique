@extends('layouts.app-v2')
@section('content-v2')
    @php
        $grandTotal = 0;
    @endphp
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Products</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('sale.index')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sale.salesrep')}}">Sale Representative</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Report</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Sales Items Report </h4>
                </div>
                <div class="card-body">
                    <div class="card-block table-dash">
                        <div class="table-responsive">
                            <table  class="table mb-0 border-0 datatable custom-table table-striped">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Product <br> Code</th>
                                    <th>Product Name</th>
                                    <th>Target <br> Units</th>
                                    <th>Achieved  <br> Units</th>
                                    <th>Target <br> Value <br> (Ksh)</th>
                                    <th>Achieved  <br> Value <br> (ksh)</th>
                                    <th style="font-size: large">(%)</th>
                                    <th>View <br> More</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $counter = 1;
                                    $totalTargetValue = 0;
                                    $totalQuantity = 0;
                                    $totalPerformance = 0;
                                    $achievedPriceTotal = 0;
                                @endphp

                                @foreach ($groupedItems as $productCode => $item)
                                    <tr style="font-size: 13px">
                                        <td >{{ $counter++ }}</td>
                                        <td >{{ $item['product_code'] }}</td>
                                        <td>{{ $item['product'] }}</td>
                                        <td >{{ $item['total_target'] }}</td>
                                        <td >{{ $item['total_quantity'] }}</td>
                                        <td >{{ number_format($item['target_value'], 2, '.', ',') }}</td>
                                        <td >{{ number_format($item['achieved_value'], 2, '.', ',') }}</td>
                                        <td class="{{ $item['percentage_performance'] > 70 ? 'text-success' : ($item['percentage_performance'] < 70 ? 'text-danger' : '') }}">
                                            {{ number_format($item['percentage_performance'], 0) }}%
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('sale.reportfacilities', ['userId' => $user_id, 'productCode' => $item['product_code']]) }}">
                                                        <i class="fa-solid fa-eye m-r-5" style="color:deepskyblue; font-size: 18px;"></i>View
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $totalQuantity += $item['total_quantity'];
                                        $totalTargetValue += $item['target_value'];
                                        $achievedPriceTotal += $item['achieved_value'];
                                        $totalPerformance += $item['percentage_performance'];
                                    @endphp

                                @endforeach

                                {{-- Total row --}}
                                <tr style="font-weight: bold;">
                                    <td style="font-weight: bold;" colspan="3">Totals:</td>
                                    <td></td>
                                    <td></td>
                                    <td style="font-weight: bold;"><b>{{ number_format($totalTargetValue, 2, '.', ',') }}</b></td>
                                    <td style="font-weight: bold;">{{ number_format($achievedPriceTotal, 2, '.', ',') }}</td> <!-- Achieved Price Total -->
                                    <td class="text-info-emphasis" style="font-weight: bold;">
                                        {{ $totalTargetValue != 0 ? number_format(($achievedPriceTotal / $totalTargetValue) * 100, 0) : 0 }}%
                                    </td> <!-- Percentage Performance Average -->
                                    <td></td>
                                </tr>
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
