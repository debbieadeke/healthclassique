@php(extract($data))
@extends('layouts.app-print',['pagetitle'=>$pagetitle])

@section('content')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
            </div>
        </div>

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="invoice-124" id="printableArea">
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="">
                                <address>
                                    <h5 class="fw-bold">Product: {{$production_order->productionsetting->product->name}}</h5>
                                    <h6 class="fw-bold invoice-customer">
                                        Batch Quantity: {{number_format($production_order->production_quantity_target, 0)}}
                                    </h6>
                                    <h6 class="fw-bold invoice-customer">
                                        Batch Cost: Ksh {{number_format($production_order->total_batch_cost, 2)}}
                                    </h6>
                                    <h6 class="fw-bold invoice-customer">
                                        Status: {{ucfirst($production_order->status)}}
                                    </h6>
                                </address>
                            </div>
                            <div class="text-end">
                                <address>
                                    @if($production_order->status == "finalized")
                                        <a href="{{ route('production-order.print', ['production_order' => $production_order->id]) }}">Print <i class="fa-solid fa-print fa-lg"></i></a>
                                    @endif
                                </address>
                            </div>
                        </div>
                        <form action="{{route('production-order.update', $production_order->id)}}" method="post" id="myForm">
                            @csrf
                            @method('PUT')
                        <div class="col-md-12">
                            <div class="table-responsive mt-1" style="clear: both">

                                <table class="table table-hover table-bordered border-dark">
                                    <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Description</th>
                                        <th class="text-end">Percentage</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-center text-success

">Actual Weight</th>
                                    </tr>
                                    <!-- end row -->
                                    </thead>
                                    <tbody>


                                        @foreach($production_order->productionorderdetails as $order_details)
                                        <tr>
                                            <td class="text-center">{{$loop->iteration}}</td>
                                            <td>{{$order_details->input->name}}</td>
                                            <td class="text-end">{{$order_details->percentage}}</td>
                                            <td class="text-end">{{$order_details->weight}}</td>
                                            <td class="text-end">
                                                @if($production_order->status == "draft")
                                                    <input type="text" class="form-control text-end" name="actual_weights[]" value="0" style="width:70px; background-color: #13DEB9">
                                                @else
                                                    <span class="text-success">{{$order_details->actual_weight}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach


                                    <!-- end row -->
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row p-2">
                                <div class="col">
                                    PH
                                </div>
                                <div class="col">
                                    {{$production_order->ph}}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Viscocity
                                </div>
                                <div class="col">
                                    {{$production_order->viscocity}}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Colour
                                </div>
                                <div class="col">
                                    {{$production_order->color}}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Smell
                                </div>
                                <div class="col">
                                    {{$production_order->smell}}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Accidental Losses
                                </div>
                                <div class="col">
                                    {{$production_order->accidental_losses}}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Expiry Date
                                </div>
                                <div class="col">
                                    {{
                                        \Carbon\Carbon::parse($production_order->expiry_date)->format('d M Y')
                                        }}
                                </div>
                                <div class="col">

                                </div>
                            </div>

                            <div class="row p-2">
                                <div class="col">
                                    Comments
                                </div>
                                <div class="col">
                                    {{$production_order->comments}}
                                </div>
                                <div class="col">

                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="col-md-12">
                            <div class="pull-right mt-1 text-end">
                                <p style="visibility:hidden">Sub - Total amount: $20,858</p>
                                <p style="visibility:hidden">vat (10%) : $2,085</p>
                                <hr style="visibility:hidden" />
                                <h3><b>Total :</b> {{number_format($production_order->total_batch_cost, 2)}}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
    <script>
        function updateProdOrderDetails() {
            const selectElement = document.getElementById("product_setting_id");
            if (selectElement.value === "select_item") {
                document.getElementById("no_of_phases_div").style.display = "none";
                document.getElementById("product_category_div").style.display = "none";
            } else {
                const selectedIndex = selectElement.selectedIndex;
                const selectedOption = selectElement.options[selectedIndex];
                const noOfPhasesInfo = selectedOption.getAttribute("data-extra-no_of_phases");
                const categoryInfo = selectedOption.getAttribute("data-extra-category");

                const inputElement1 = document.getElementById("no_of_phases");
                if (inputElement1) {
                    inputElement1.value = noOfPhasesInfo;
                }
                const inputElement2 = document.getElementById("category");
                if (inputElement2) {
                    inputElement2.value = categoryInfo;
                }
            }
        }
    </script>
@stop
