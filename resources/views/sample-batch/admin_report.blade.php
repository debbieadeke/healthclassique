@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Samples Report</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                                    <li class="breadcrumb-item active" aria-current="page">Samples Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-3">
                            <div class="text-center mb-n5">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-none position-relative overflow-hidden">
            <form action="{{route('sample-batch.adminReport')}}" method="post" id="myForm">
                @csrf
                <div class="col-12 col-md-12  col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="card-title d-inline-block">Marketing Sample Report</h4>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button onclick="printTable()" class="btn btn-primary">Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="row col-md-12 table-responsive" id="print-container">
                                <div class="row">
                                    <label for="user_id" class="col-form-label col-12 col-md-2">Select Date Range</label>
                                    <div class="col-12 col-md-5">
                                        <div class="input-group">
                                            <input type="date" id="filter_date" style="font-size: 12px" class="form-control" name="filter_date" value="{{ $filterDate }}">
                                            <input type="date" id="end_date" style="font-size: 12px" class="form-control" name="end_date" value="{{ $endDate }}">
                                            <input type="hidden" name="filter" value="is_on">
                                            <button class="btn btn-light-info text-info font-medium" type="submit">Go!</button>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                    <thead>
                                    <tr style="font-size: 14px">
                                        <th style="width: 5px">No</th>
                                        <th >User</th>
                                        <th>Product</th>
                                        <th>Qty <br> Rqst</th>
                                        <th>Qty <br> Appd</th>
                                        <th>Qty <br> Issued</th>
                                        <th>Date <br> Requested</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($samples as $sample)
                                            <tr style="font-size: 13px">
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$sample->user->first_name}} {{$sample->user->last_name}}</td>
                                                <td>{{$sample->product->name}}</td>
                                                <td>{{$sample->quantity_requested}}</td>
                                                <td>{{$sample->quantity_approved}}</td>
                                                <td>{{$sample->quantity_issued}}</td>
                                                <td>{{ \Carbon\Carbon::parse($sample->created_at)->format('jS F Y h:i A') }}</td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <!-- Table footer content here -->
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function printTable() {
            $('#myDataTable_wrapper .dataTables_filter').hide();
            $('#myDataTable_wrapper .dataTables_paginate').hide();
            $('#myDataTable_wrapper .dataTables_length').hide();
            var originalContents = document.body.innerHTML;
            var printContents = document.getElementById("print-container").innerHTML;

            // Add title and logo to the print contents
            var titleLogoContainer = "<div class='title-logo-container' style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;'>" +
                "<h2>Marketing Samples Request Form</h2>" +
                "<img src='{{ asset('assets-v2/img/logo.png') }}' alt='Logo' style='float-right' class='logo'>" +
                "</div>";

            // Add Dispatched by and Received by sections with signature lines on the same line
            var dispatchAndReceiveSection = "<div style='margin-top: 50px;'>" +
                "<div style='float: left; width: 50%;'><h4>Dispatched by:</h4><p>Signature: _________________________</p></div>" +
                "<div style='float: right; width: 50%;'><h4>Received by:</h4><p>Signature: _________________________</p></div>" +
                "<div style='clear: both;'></div>" +
                "</div>";

            // Combine all sections
            printContents = titleLogoContainer + printContents + dispatchAndReceiveSection;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            $('#myDataTable_wrapper .dataTables_filter').show();
            $('#myDataTable_wrapper .dataTables_paginate').show();
            $('#myDataTable_wrapper .dataTables_length').show();
        }
    </script>
    <style>
        /* CSS for the title and logo container */
        .title-logo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px; /* Adjust as needed */
        }

        /* CSS for the logo */
        .logo {
            max-width: 100px; /* Adjust as needed */
        }
    </style>
@endsection

