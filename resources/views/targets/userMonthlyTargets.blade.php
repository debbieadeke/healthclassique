@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Facilities Targets</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" ><a href="{{route('targets.sales_rep_target')}}">My Monthly Targets </a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Facilities Targets</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">Facilities Targets</h4>
                </div>
                <div class="card-body">
                    <div class="card-block table-dash">
                        <div class="p-2">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Facilities Code</th>
                                    <th>Facilities Name</th>
                                    <th>Target</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $counter = 1;
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
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($entry['facility']))
                                                {{ $entry['facility']->name }}
                                            @elseif (isset($entry['pharmacy']))
                                                {{ $entry['pharmacy']->name }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($entry['target']) }}</td>
                                    </tr>
                                    @php
                                        $totalTargetValue += $entry['target'];
                                    @endphp
                                @endforeach
                                {{-- Total row --}}
                                <tr style="font-weight: bold;">
                                    <td style="font-weight: bold;" colspan="3">Totals:</td>
                                    <td style="font-weight: bold;">{{ $totalTargetValue }}</td>
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