@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1> Monthly Full Sales Report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('sale.monthlyReport_index')}}">Sales Representatives <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Monthly Full Sales Items</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-semibold">Full Sales Report</h5>
                    <div class="filter-section d-flex">
                        <form id="filterForm" action="{{route('sale.monthlyReportFilter',['userId' => $user_id])}}" method="GET" class="d-flex">
                            <div class="mb-3 me-3">
                                <select class="form-select" id="month" name="month">
                                    @foreach($months as $key => $month)
                                        <option name="selected_month" value="{{ $key }}" {{ $key == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 me-3">
                                <select class="form-select" id="year" name="year">
                                    @foreach($years as $year)
                                        <option name="selected_year" value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Filter section -->

                    <!-- End Filter section -->

                    <div class="card">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="p2">
                                            <div class="p-2">
                                                <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
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
                                                                        <a class="dropdown-item" href="{{ route('sale.monthlyReportfacilities', ['userId' => $user_id, 'productCode' => $item['product_code'],'month'=>$currentMonth, 'year'=>$currentYear]) }}">
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
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
