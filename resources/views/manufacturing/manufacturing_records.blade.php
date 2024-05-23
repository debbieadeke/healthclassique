@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Manufacturing Records</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Manufacturing Records</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="table-responsive">
                        <table class="table caption-top table-striped table-bordered mb-0 text-center table-to-modify">
                            <caption class="mixing-temp"><b>MIXING TEMP</b></caption>
                            <thead>
                            <tr>
                                <th scope="col" class="small-width">PHASE A</th>
                                <th scope="col" class="small-width">PHASE B</th>
                                <th scope="col" class="medium-width">START MIX TEMP</th>
                                <th scope="col" class="small-width">PHASE C</th>
                                <th scope="col" class="small-width">PHASE D</th>
                                <th scope="col" class="large-width">GENERAL NOTES, COMMENTS AND OBSERVATION</th>
                                <th scope="col" class="smaller-width"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="phase_A" class="var text-center"></td>
                                <td><input type="text" name="phase_B" class="var text-center"></td>
                                <td><input type="text" name="start_mix_temp" class="var text-center"></td>
                                <td><input type="text" name="phase_C" class="var text-center"></td>
                                <td><input type="text" name="phase_D" class="var text-center"></td>
                                <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
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
                        <button type="button" class="btn btn-primary mt-3 add-row-button">Add Row</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table caption-top table-striped table-bordered mb-0 mt-3 text-center table-to-modify2">
                            <caption class="mixing-temp"><b>HOMOGENEOUS PARAMETER</b></caption>
                            <thead>
                            <tr>
                                <th scope="col" class="small-width1">START SPEED/T</th>
                                <th scope="col" class="small-width2">SUB SPEED/T/INTVLS</th>
                                <th scope="col" class="small-width">STIRRING START</th>
                                <th scope="col" class="small-width">FINAL STIRRING</th>
                                <th scope="col" class="large-width">GENERAL NOTES, COMMENTS AND OBSERVATION</th>
                                <th scope="col" class="smaller-width"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="start_speed" class="var text-center"></td>
                                <td><input type="text" name="sub_speed" class="var text-center"></td>
                                <td><input type="text" name="stirring" class="var text-center"></td>
                                <td><input type="text" name="final" class="var text-center"></td>
                                <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
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
                    <div class="table-responsive">
                        <table class="table caption-top table-striped table-bordered mb-0 mt-3 text-center table-to-modify3">
                            <caption class="mixing-temp"><b>COOLING PARAMETERS</b></caption>
                            <thead>
                            <tr>
                                <th scope="col" class="small-width">H20 FLOW RT</th>
                                <th scope="col" class="small-width">START T/TEMP</th>
                                <th scope="col" class="small-width">END T/TEMP</th>
                                <th scope="col" class="small-width">COOLING TIME</th>
                                <th scope="col" class="large-width">GENERAL NOTES, COMMENTS AND OBSERVATION</th>
                                <th scope="col" class="smaller-width"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" name="flow_rt" class="var text-center"></td>
                                <td><input type="text" name="start_temp" class="var text-center"></td>
                                <td><input type="text" name="end_temp" class="var text-center"></td>
                                <td><input type="text" name="cooling" class="var text-center"></td>
                                <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
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
                        <button type="button" class="btn btn-primary mt-2 add-row-button3">Add Row</button>
                    </div>
                </form>
                <div class="row mt-5 mb-3">
                    <div class="col-md-2 ">
                        <div>
                            <span class="process"><b>Process Start Time:</b></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="time" class="form-control black-border time">
                    </div>

                    <div class="col-md-2">
                        <div>
                            <span class="process"><b>Process End Time:</b></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="time" class="form-control black-border time">
                    </div>
                    <div class="col-md-2">
                        <div>
                            <span class="process-at"><b>Final Sample Submitted at:</b></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="time" class="form-control black-border final">
                    </div>



                </div>
                <div class="row mt-5 mb-3 ">
                    <div class="col-md-3 process"><b>General Comment:</b></div>
                    <div class="col-md-9">
                        <input type="text" class="form-control wide-input black-border ant">
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
        .mixing-temp{
            color:#000000;
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
        .small-width1{
            width: 10%;
            height: 40px;
        }
        .small-width2{
            width: 13%;
            height: 40px;
        }
        .medium-width {
            width: 15%;
            height: 40px;
        }
        .large-width {
            width: 35%;
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
        .remarks{
            font-size:12px;
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
        .process{
            font-size:13px;
            margin-left:-2px;
        }
        .process-at{
            font-size:13px;
            margin-left:-18px;
        }
        .time{
            margin-left:-60px;
        }
        .ant{
            margin-left:-144px;
        }
        .final{
            margin-left:-25px;
        }
        .black-border{
            border-color:#000000 !important;
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

        document.querySelector('.add-row-button3').addEventListener('click', function() {
            var table = document.querySelector('.table-to-modify3');
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

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.table-to-modify3').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.preventDefault();
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>

@endsection


