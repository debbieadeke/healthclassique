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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('sale.quarter-report')}}">Sale Representative</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Quarterly Sales items Report <i class="fas fa-angle-right"></i></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="row card-header pb-0">
                    <div class="col-md-10">
                        <h4 class="card-title d-inline-block">Quarterly Sales items Report </h4>
                    </div>
                    <div class="col-md-2" style="padding-bottom: 20px">
                        <label for="quarter-select" class="form-label">Select Quarter</label>
                        <select class="form-select" id="quarter-select" onchange="filterTableBySelectedQuarter(this.value)">
                            <option value="1" selected>1st Quarter</option>
                            <option value="2">2nd Quarter</option>
                            <option value="3">3rd Quarter</option>
                            <option value="4">4th Quarter</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-dash">
                        <div class="table-responsive">
                            <table  class="table mb-0 border-0 datatable custom-table table-striped" id="quarter-table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product <br> Code</th>
                                    <th>Product Name</th>
                                    <th>Quarter <br> Target <br> Units</th>
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
                                    $selectedQuarter = 1;
                                    $quarterTotals = [
                                            1 => ['targetValue' => 0, 'achievedValue' => 0],
                                            2 => ['targetValue' => 0, 'achievedValue' => 0],
                                            3 => ['targetValue' => 0, 'achievedValue' => 0],
                                            4 => ['targetValue' => 0, 'achievedValue' => 0]
                                        ];
                                @endphp

                                @foreach ($groupedItems as $productCode => $item)
                                    <tr style="font-size: 3px" data-quarter="{{ $item['quarter'] }}">
                                        <td class="text-end">{{ $counter++ }}</td>
                                        <td>{{ $item['product_code'] }}</td>
                                        <td>{{ $item['product'] }}</td>
                                        <td >{{ number_format($item['total_target']) }}</td>
                                        <td >{{ number_format($item['total_quantity']) }}</td>
                                        <td >{{ number_format($item['target_value'], 2, '.', ',') }}</td>
                                        <td >{{ number_format($item['achieved_value'], 2, '.', ',') }}</td>
                                        <td class="{{ $item['percentage_performance'] < 100 ? 'text-danger' : '' }}">
                                            {{ number_format($item['percentage_performance']) }}%
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('sale.quarterReportFacilities', ['userId' => $user_id, 'productCode' => $item['product_code']]) }}">
                                                        <i class="fa-solid fa-eye m-r-5" style="color:deepskyblue; font-size: 18px;"></i>View
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                         $quarter = (int)substr($item['quarter'], 1);
                                         $quarterTotals[$quarter]['targetValue'] += $item['target_value'];
                                         $quarterTotals[$quarter]['achievedValue'] += $item['achieved_value'];
                                    @endphp

                                @endforeach
                                <tr style="font-weight: bold;">
                                    <td colspan="3">Totals:</td>
                                    <td></td>
                                    <td></td>
                                    <td><b>{{ number_format($totalTargetValue, 2, '.', ',') }}</b></td>
                                    <td>{{ number_format($achievedPriceTotal, 2, '.', ',') }}</td> <!-- Achieved Price Total -->
                                    <td>{{  $totalTargetValue != 0 ? number_format(($achievedPriceTotal / $totalTargetValue) * 100, 0) : 0 }}%</td> <!-- Percentage Performance Average -->
                                    <td></td>
                                </tr>
                                </tbody>
                                @foreach ($quarterTotals as $quarter => $totals)
                                    <tfoot data-quarter="Q{{ $quarter }}" style="display: none;">
                                    <tr>
                                        <td style="font-weight: bold;" colspan="5">Total for Quarter {{ $quarter }}:</td>
                                        <td style="font-weight: bold;" >{{ number_format($totals['targetValue'], 2, '.', ',') }}</td>
                                        <td style="font-weight: bold;">{{ number_format($totals['achievedValue'], 2, '.', ',') }}</td>
                                        <td class="text-info-emphasis" style="font-weight: bold;">{{ $totals['targetValue'] != 0 ? number_format(($totals['achievedValue'] / $totals['targetValue']) * 100, 0) : 0 }}%</td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function filterTableBySelectedQuarter(selectedQuarter) {
            // Convert selectedQuarter value to match quarter format in data
            var quarterMapping = {
                '1': 'Q1',
                '2': 'Q2',
                '3': 'Q3',
                '4': 'Q4'
            };
            var selectedQuarterDataFormat = quarterMapping[selectedQuarter];

            $('#quarter-table tfoot').hide();

            // Show footer row for the selected quarter
            $('#quarter-table tfoot[data-quarter="' + selectedQuarterDataFormat + '"]').show();

            var tableRows = $('#quarter-table tbody tr');
            tableRows.each(function() {
                var rowQuarter = $(this).data('quarter'); // Assuming 'quarter' is a data attribute in each row
                if (rowQuarter === selectedQuarterDataFormat || selectedQuarter === 'all') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        window.onload = function() {
            // Initially filter the table based on the default selected quarter
            var selectedQuarter = 1; // Ensure the quarter value is within quotes
            filterTableBySelectedQuarter(selectedQuarter);
        };
    </script>
@endsection

