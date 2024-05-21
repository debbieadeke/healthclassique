@extends('layouts.app-v2')
@section('content-v2')
    @php
        $grandTotal = 0;
    @endphp
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Monthly Targets</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">My Monthly Targets</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title d-inline-block">Monthly Targets</h4>
                    <div class="filter-section d-flex">
                        <form id="filterForm" action="{{route('targets.monthly_user_target_filter')}}" method="GET" class="d-flex">
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
                    <div class="card-block p-2">
                        <div class="p-2">
                            <table class="table table-responsive table-dash mb-0 border-0 display">
                                <thead>
                                <tr style="font-size: 14px" >
                                    <th>No</th>
                                    <th>Product <br> Code</th>
                                    <th>Product Name</th>
                                    <th>Target <br> Units</th>
                                    <th>Target <br> Value (Ksh)</th>
                                    <th>View More</th>
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
                                        <td >{{ number_format($item['target_value'], 2, '.', ',') }}</td>

                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('targets.user_monthly_target', ['userId' => $user_id, 'productCode' => $item['product_code'],'month'=>$currentMonth, 'year'=>$currentYear]) }}">
                                                        <i class="fa-solid fa-eye m-r-5" style="color:black; font-size: 12px;"></i>View
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $totalQuantity += $item['total_quantity'];
                                        $totalTargetValue += $item['target_value'];
                                    @endphp

                                @endforeach

                                {{-- Total row --}}
                                <tr style="font-weight: bold;">
                                    <td style="font-weight: bold;" colspan="3">Totals:</td>
                                    <td></td>
                                    <td style="font-weight: bold;"><b>{{ number_format($totalTargetValue, 2, '.', ',') }}</b></td>
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
