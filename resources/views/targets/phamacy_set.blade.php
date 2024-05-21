@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        @if(isset($success))
            <div class="alert alert-success">
                {{ $success }}
            </div>
        @endif
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>My Pharmacy Sales Target</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item"><a href="{{route('targets.pharmacy')}}">Select a Pharmacy</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Pharmacy Sales Target</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <h5 class="card-title">My Pharmacy targets for <b>{{ $name }} {{ $currentYear }} </b></h5>
                    </div>
                    <div class="col-md-2" style="padding-bottom: 20px">
                        <label for="quarterDropdown" class="form-label">Select Quarter</label>
                        <select class="form-select" id="quarterDropdown" onchange="displayColumns()">
                            <option value="q1" selected>1st Quarter</option>
                            <option value="q2">2nd Quarter</option>
                            <option value="q3">3rd Quarter</option>
                            <option value="q4">4th Quarter</option>
                        </select>
                    </div>
                </div>
                <div class="">

                    <div class="p-2">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead class="text-dark fs-15">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Product Name</h6>
                                </th>
                                <th id="q1" class="border-bottom-0 q1">
                                    <h6 class="fw-semibold mb-0 ">January</h6>
                                </th>
                                <th id="q1" class="border-bottom-0 q1">
                                    <h6 class="fw-semibold mb-0">February</h6>
                                </th>
                                <th id="q1" class="border-bottom-0 q1">
                                    <h6 class="fw-semibold mb-0">March</h6>
                                </th>
                                <th id="q1" class="border-bottom-0 q1">
                                    <h6 class="fw-semibold mb-0 ">Total Targets <br> (1<sup>st</sup>Quarter)</h6>
                                </th>
                                <th id="q2" class="border-bottom-0 q2">
                                    <h6 class="fw-semibold mb-0">April</h6>
                                </th>
                                <th id="q2" class="border-bottom-0 q2">
                                    <h6 class="fw-semibold mb-0 ">May</h6>
                                </th>
                                <th id="q2" class="border-bottom-0 q2">
                                    <h6 class="fw-semibold mb-0 ">June</h6>
                                </th>
                                <th id="q2" class="border-bottom-0 q2">
                                    <h6 class="fw-semibold mb-0">Total Targets <br> (2<sup>st</sup>Quarter)</h6>
                                </th>
                                <th class="border-bottom-0 q3">
                                    <h6 class="fw-semibold mb-0">July</h6>
                                </th>
                                <th class="border-bottom-0 q3">
                                    <h6 class="fw-semibold mb-0">August</h6>
                                </th>
                                <th class="border-bottom-0 q3">
                                    <h6 class="fw-semibold mb-0">September</h6>
                                </th>
                                <th class="border-bottom-0 q3">
                                    <h6 class="fw-semibold mb-0 q3">Total Targets <br> (3<sup>st</sup>Quarter)</h6>
                                </th>
                                <th class="border-bottom-0 q4">
                                    <h6 class="fw-semibold mb-0">October</h6>
                                </th>
                                <th class="border-bottom-0 q4">
                                    <h6 class="fw-semibold mb-0" >November</h6>
                                </th>
                                <th class="border-bottom-0 q4">
                                    <h6 class="fw-semibold mb-0">December</h6>
                                </th>
                                <th class="border-bottom-0 q4">
                                    <h6 class="fw-semibold mb-0">Total Targets <br> (4<sup>st</sup>Quarter)</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Actions</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($products) > 0)
                                @foreach($products as $product )
                                    <tr class="fs-18" style="font-size: 13px">
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $loop->iteration }}</h6></td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $product->name }}</span>
                                        </td>
                                        @foreach(['january', 'february', 'march'] as $month)
                                            <td id="q1">
                                                @php
                                                    $target = collect($product->monthlyTargets)->firstWhere('month', $month);
                                                @endphp

                                                @if ($target)
                                                    <span class="fw-normal">{{ $target['target'] }}</span>
                                                @else
                                                    <span class="text-danger">0</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td id="q1" class="border-bottom-0">
                                            @php
                                                $targetMonths = ['january', 'february', 'march'];
                                                $filteredTargets = collect($product->monthlyTargets)
                                                    ->whereIn('month', $targetMonths)
                                                    ->unique('month');

                                                $total = $filteredTargets->sum('target');
                                            @endphp

                                            @if ($total > 0)
                                                <span class="text-success">{{ $total }}</span>
                                            @else
                                                <span class="text-danger">0</span>
                                            @endif
                                        </td>
                                        @foreach(['april','may', 'june'] as $month)
                                            <td id="q2">
                                                @php
                                                    $target = collect($product->monthlyTargets)->firstWhere('month', $month);
                                                @endphp

                                                @if ($target)
                                                    <span class="fw-normal">{{ $target['target'] }}</span>
                                                @else
                                                    <span class="text-danger">0</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td id="q2" class="border-bottom-0">
                                            @php
                                                $targetMonths = ['april','may', 'june'];
                                                $filteredTargets = collect($product->monthlyTargets)
                                                    ->whereIn('month', $targetMonths)
                                                    ->unique('month');

                                                $total = $filteredTargets->sum('target');
                                            @endphp

                                            @if ($total > 0)
                                                <span class="text-success">{{ $total }}</span>
                                            @else
                                                <span class="text-danger">0</span>
                                            @endif
                                        </td>
                                        @foreach(['july', 'august','september'] as $month)
                                            <td id="q3">
                                                @php
                                                    $target = collect($product->monthlyTargets)->firstWhere('month', $month);
                                                @endphp

                                                @if ($target)
                                                    <span class="fw-normal">{{ $target['target'] }}</span>
                                                @else
                                                    <span class="text-danger">0</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td id="q3" class="border-bottom-0">
                                            @php
                                                $targetMonths = ['july', 'august','september'];
                                                $filteredTargets = collect($product->monthlyTargets)
                                                    ->whereIn('month', $targetMonths)
                                                    ->unique('month');

                                                $total = $filteredTargets->sum('target');
                                            @endphp

                                            @if ($total > 0)
                                                <span class="text-success">{{ $total }}</span>
                                            @else
                                                <span class="text-danger">0</span>
                                            @endif
                                        </td>
                                        @foreach(['october', 'november', 'december'] as $month)
                                            <td id="q4">
                                                @php
                                                    $target = collect($product->monthlyTargets)->firstWhere('month', $month);
                                                @endphp

                                                @if ($target)
                                                    <span class="fw-normal">{{ $target['target'] }}</span>
                                                @else
                                                    <span class="text-danger">0</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td id="q4" class="border-bottom-0">
                                            @php
                                                $targetMonths = ['october', 'november', 'december'];
                                                $filteredTargets = collect($product->monthlyTargets)
                                                    ->whereIn('month', $targetMonths)
                                                    ->unique('month');

                                                $total = $filteredTargets->sum('target');
                                            @endphp

                                            @if ($total > 0)
                                                <span class="text-success">{{ $total }}</span>
                                            @else
                                                <span class="text-danger">0</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('targets.set',['id' =>$product->id, 'code' => $code]) }}"><i class="fas fa-square-plus" style="color:green; font-size: 18px;"></i> Set Target</a>
                                                     <a class="dropdown-item" href="{{ route('targets.edit',['id' =>$product->id, 'code' => $code]) }}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Target</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No Products Check If you have selected items in customer Manage Pharmacies</h6></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .icon-container {
            position: relative;
            display: inline-block;
        }

        .icon-container .tooltip {
            visibility: hidden;
            width: 80px;
            background-color: #3676f3;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -40px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .icon-container:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .pagination li {
            margin: 0 5px;
            font-size: 14px;
        }

        .pagination .page-link {
            padding: 5px 10px;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .pagination-link,
        .pagination-link-disabled {
            display: inline-flex;
            items-align: center;
            padding: 8px;
            margin: 5px;
            font-size: 14px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination-link:hover {
            background-color: #f8f9fa;
        }
        .pagination-link {
            text-decoration: none;
            padding: 0.25rem 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
            &:hover {
                background-color: #007bff;
                color: #fff;
                border: 1px solid transparent;
            }
        }
        .font-bold {
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            displayColumns(); // Call the function on page load

            $("#quarterDropdown").change(function() {
                displayColumns();
            });

            function displayColumns() {
                var selectedQuarter = $("#quarterDropdown").val();

                // Hide all columns and headers
                $("td[id^='q'], th[class*='q']").hide();

                // Show columns for the selected quarter
                $("td[id^='" + selectedQuarter + "']").show();

                // Show headers for the selected quarter
                $("th[class*='" + selectedQuarter + "']").show();
            }
        });
    </script>
@endsection
