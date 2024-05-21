@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Facilities</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('sale.fullreport_index')}}">Sales Representatives <i class="fas fa-angle-right"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Full Sales items Report<i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Customer</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Full Report Sales Facilities </h4>
                </div>
                <div class="card-body">
                    <div class="card-block p-2">
                        <div class="p-2">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Facilities Code</th>
                                    <th>Facilities Name</th>
                                    <th>Target</th>
                                    <th>Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $counter = 1;
                                    $totalQuantity = 0;
                                    $totalTargetValue = 0;
                                @endphp
                                @foreach ($report as $entry)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            @if (isset($entry['facility']))
                                                {{ $entry['facility']->code }}
                                            @elseif (isset($entry['pharmacy']))
                                                {{ $entry['pharmacy']->code }}
                                            @else
                                                {{ $entry->customer_code }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($entry['facility']))
                                                {{ $entry['facility']->name }}
                                            @elseif (isset($entry['pharmacy']))
                                                {{ $entry['pharmacy']->name }}
                                            @else
                                                {{ $entry->customer_name }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($entry['target']) }}</td>
                                        <td>{{ number_format($entry['sales_quantity']) }}</td>
                                    </tr>
                                    @php
                                        $totalQuantity += $entry['sales_quantity'];
                                        $totalTargetValue += $entry['target'];
                                    @endphp
                                @endforeach
                                {{-- Total row --}}
                                <tr style="font-weight: bold;">
                                    <td style="font-weight: bold;" colspan="3">Totals:</td>
                                    <td style="font-weight: bold;">{{ $totalTargetValue }}</td>
                                    <td style="font-weight: bold;">{{ $totalQuantity }}</td>
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
@endsection
<script>
    $(document).ready(function() {
        $('#myDataTable').DataTable({
            "pageLength": 50
        });
    });
</script>
