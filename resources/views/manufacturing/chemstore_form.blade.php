@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>CHEMICAL STORE FORM</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">CHEMICAL STORE FORM</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-4 mb-4">
                        <label for="product_id"><b>Product Name</b></label>
                        <input type="text" placeholder="" name="product_quantity_target"
                               id="product_quantity_target" value="" class="form-control w-100 mt-2"
                               required />
                    </div>
                    <div class="col-4 ">
                        <label for="product_id" ><b>Weighed By </b></label>
                        <input type="text" placeholder="" name="product_quantity_target"
                               id="product_quantity_target" value="" class="form-control w-100 mt-2"
                               required />
                    </div>
                    <div class="col-4">
                        <label for="product_id"><b>Batch No.</b></label>
                        <input type="text" placeholder="" name="product_quantity_target"
                               id="product_quantity_target" value="" class="form-control w-100 mt-2"
                               required />
                    </div>

                <form action="">
                    <table class="table table-striped table-bordered mb-0 text-center">
                        <thead>
                        <tr>
                            <th scope="col" class="medium-small-width">MATERIAL CODE/BATCH & <br>EXPIRY DATE</th>
                            <th scope="col" class="medium-width">STANDARD<br>WEIGHT (G)</th>
                            <th scope="col" class="medium-width">WEIGHED<br>QTY (G)</th>
                            <th scope="col" class="smaller-width">VARIANCE <br> (G)</th>
                            <th scope="col" class="smaller-width">SUPPLIER<br>CODE</th>
                            <th scope="col" class="large-width">RAW MATERIALS QUALITY &<br>QUANTITY REMARKS</th>
                            <th scope="col" class="small-width"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><input type="text" name="material_code" value="SOLV-000/123456789LLL/5-24" readonly class="text-center var" ></td>
                            <td><input type="text" name="standard_weight" value="498,777.00" readonly class="var text-center"></td>
                            <td><input type="text" name="weighed_quantity" value="" class="var"></td>
                            <td><input type="text" name="vrc" value="1,234.00" readonly class="var text-center"></td>
                            <td><input type="text" name="supplier_code" value="HC028" readonly class="var text-center"></td>
                            <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary continue">Continue</button>
                            </td>

                        </tr>
                        <tr>
                            <td><input type="text" name="material_code" value="S005" readonly class="text-center var" ></td>
                            <td><input type="text" name="standard_weight" value="" readonly class="var"></td>
                            <td><input type="text" name="weighed_quantity" value="" class="var"></td>
                            <td><input type="text" name="vrc" value="" readonly class="var"></td>
                            <td><input type="text" name="supplier_code" value="" readonly class="var"></td>
                            <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary continue">Continue</button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" name="material_code" value="S005" readonly class="text-center var" ></td>
                            <td><input type="text" name="standard_weight" value="" readonly class="var"></td>
                            <td><input type="text" name="weighed_quantity" value="" class="var"></td>
                            <td><input type="text" name="vrc" value="" readonly class="var"></td>
                            <td><input type="text" name="supplier_code" value="" readonly class="var"></td>
                            <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary continue">Continue</button>
                            </td>
                        </tr>

                        <tr>
                            <td><input type="text" name="material_code" value="S005" readonly class="text-center var" ></td>
                            <td><input type="text" name="standard_weight" value="" readonly class="var"></td>
                            <td><input type="text" name="weighed_quantity" value="" class="var"></td>
                            <td><input type="text" name="vrc" value="" readonly class="var"></td>
                            <td><input type="text" name="supplier_code" value="" readonly class="var"></td>
                            <td><textarea name="input1-8" maxlength="100" class="remarks"></textarea></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary continue">Continue</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
                <div class="row">
                    <div class="col-md-4"><h4>Name: Charles</h4></div>
                    <div class="col-md-4" >
                        Password: <input type="text"></div>
                    <div class="col-md-4 text-end"><button type="button" class="btn btn-primary">Sign</button></div>
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
            .smaller-width {
                width: 8%;
                height: 40px;
            }
            .medium-small-width {
                width: 20%;
                height: 40px;
            }
            .medium-width {
                width: 11%;
                height: 40px;
            }
            .large-width {
                width: 23%;
                height: 40px;
            }
            .small-width{
                width:8%;
                height:40px;
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
            .continue{
                border-radius:20px;
            }
            input[type="text"]{
                border-radius: 5px;
                border: 1px solid #DEDFEA;

            }
        </style>
@endsection


    </div>
