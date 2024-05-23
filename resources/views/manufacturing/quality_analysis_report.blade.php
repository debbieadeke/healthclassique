@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Quality Analysis Report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Quality Analysis Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="table-responsive">
                        <table class="table caption-top table-striped table-bordered mb-0 text-center table-to-modify">
                            <thead>
                            <tr>
                                <th scope="col" class="medium-width2">APPEARANCE</th>
                                <th scope="col" >COLOR</th>
                                <th scope="col" >ODOR</th>
                                <th scope="col" class="medium-width" >VISCOSITY</th>
                                <th scope="col" >pH</th>
                                <th scope="col" class="medium-width">SPECIFIC GRAVITY</th>
                                <th scope="col" class="medium-width2">TOTAL VIABLE COUNT</th>
                                <th scope="col" class="small-width">OTHER</th>
                                <th scope="col" class="smaller-width"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="appearance" class="var text-center"></td>
                                <td><input type="text" name="color" class="var text-center"></td>
                                <td><input type="text" name="odor" class="var text-center"></td>
                                <td><input type="text" name="viscosity" class="var text-center"></td>
                                <td><input type="text" name="ph" class="var text-center"></td>
                                <td><input type="text" name="specific-gravity" class="var text-center"></td>
                                <td><input type="text" name="viable-count" class="var text-center"></td>
                                <td><input type="text" name="other" class="var text-center"></td>

                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item remove-row"><i class="fas fa-trash-alt" style="color:black; font-size: 12px;"></i> Remove</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary mt-2 add-row-button">Add Row</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table caption-top table-striped table-bordered mb-0 mt-3 text-center table-to-modify2">
                            <thead>
                            <tr>
                                <th scope="col" class="medium-width2">TOTAL ACTIVE MATTER</th>
                                <th scope="col">RANCIDITY</th>
                                <th scope="col" >SOLUBLE MATTER</th>
                                <th scope="col" >LATHER VOLUME</th>
                                <th scope="col" class="medium-width">THERMAL STABILITY</th>
                                <th scope="col" class="small-width">OTHER</th>
                                <th scope="col" class="smaller-width"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="active_matter" class="var text-center"></td>
                                <td><input type="text" name="rancidity" class="var text-center"></td>
                                <td><input type="text" name="soluble_matter" class="var text-center"></td>
                                <td><input type="text" name="lather_volume" class="var text-center"></td>
                                <td><input type="text" name="thermal" class="var text-center"></td>
                                <td><input type="text" name="other" class="var text-center"></td>

                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item remove-row"><i class="fas fa-trash-alt" style="color:black; font-size: 12px;"></i> Remove</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary mt-2 add-row-button2">Add Row</button>
                    </div>
                </form>
                <div class="row mt-5 mb-3">
                    <div class="col-md-3">
                        <div class=" text-end">
                            <span class="process3"><b>Sample Analysis cleared At:</b></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="time" class="form-control black-border ">
                    </div>
                    <div class="col-md-3">
                        <div class=" text-end">
                            <span class="process3"><b>Batch released At:</b></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="time" class="form-control black-border ">
                    </div>
                </div>
                <div class="row mt-5 mb-3">
                    <div class="col-md-3 process2"><b>Qc Technician Comment:</b></div>
                    <div class="col-md-9">
                        <input type="text" class="form-control wide-input black-border ant2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table {
            table-layout: fixed;
            width: 100%;
        }
        .table th, .table td {
            padding: 4px;
            white-space: nowrap;
        }

        .table input[type="text"], .table textarea {
            width: 100%;
            box-sizing: border-box;
        }
        .smaller-width{
            width:4%;
            height:40px;
        }
        .small-width {
            width: 10%;
            height: 40px;
        }

        .medium-width {
            width: 15%;
            height: 40px;
        }
        .medium-width2 {
            width: 18%;
            height: 40px;
        }

        .table textarea {
            resize: none;
            height: 38px;
        }
        .var{
            height:38px;
        }
        .table input[type="text"],
        .table textarea {
            border-radius: 5px;
            border: 1px solid #DEDFEA;
            padding: 10px 2px;
            margin-top:4px;
        }

        .table input[type="text"]:focus,
        .table textarea:focus {
            border: 2px solid #37429B !important;
            outline: none;
        }
        input[type="text"]{
            border-radius: 5px;
            border: 1px solid #DEDFEA;
        }
        .process3 {
            font-size:13px;
            margin-left: -11px;
        }
        .process2{
            font-size:13px;
        }
        .ant2{
            margin-left:-100px;
        }
        .black-border{
            border-color:#000000!important;
        }

    </style>

    <script>
        document.querySelector('.add-row-button').addEventListener('click', function() {
            var table = document.querySelector('.table-to-modify');
            var newRow = table.rows[1].cloneNode(true);
            var inputs = newRow.querySelectorAll('input, textarea');
            inputs.forEach(function(input) {
                input.value = '';
            });
            table.querySelector('tbody').appendChild(newRow);
        });
        document.querySelector('.add-row-button2').addEventListener('click', function() {
            var table = document.querySelector('.table-to-modify2');
            var newRow = table.rows[1].cloneNode(true);
            var inputs = newRow.querySelectorAll('input, textarea');
            inputs.forEach(function(input) {
                input.value = '';
            });
            table.querySelector('tbody').appendChild(newRow);
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.table-to-modify').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.preventDefault();
                    e.target.closest('tr').remove();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.table-to-modify2').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.preventDefault();
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>
@endsection
