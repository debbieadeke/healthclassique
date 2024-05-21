@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Production Order</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Create
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{asset('assets/images/breadcrumb/ChatBc.png')}}" alt="" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('process')}}" method="post" id="myForm">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Double Call (Drop-down) -->
                        <div class="mb-3">
                            <label for="product_phase_id" class="form-label">Select Product</label>
                            <select class="form-control select2" style="width: 100%; height: 40px" id="product_setting_id" name="product_setting_id" required onchange="updateProdOrderDetails()">
                                <option value="select_item">Select Product</option>
                                @foreach($product_settings as $product_phase)
                                    <option class="form-control" value="{{$product_phase->id}}" data-extra-no_of_phases="{{$product_phase->no_of_phases}}" data-extra-category="Cream">{{$product_phase->product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="no_of_phases_div">
                        <label for="no_of_phases" class="form-label">No of Phases</label>
                        <input type="number" class="form-control" id="no_of_phases" name="no_of_phases" value="" max="6">
                    </div>
                    <div class="col-md-2" id="rows_per_phase_div">
                        <label for="rows_per_phase" class="form-label">No of Rows / Phase</label>
                        <input type="number" class="form-control" id="rows_per_phase" name="rows_per_phase" value="2">
                    </div>
                    <div class="col-md-2" id="product_category_div">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" readonly value="">
                    </div>
                    <div class="col-md-2">
                        <label for="order_quantity" class="form-label">Order Quantity (g)</label>
                        <input type="text" class="form-control" id="product_quantity_target" name="product_quantity_target" required>
                    </div>
                </div>
                    @csrf
                    <div class="row">
                    <div class="col-md-4">
                        &nbsp;&nbsp;
                    </div>
                    <div class="col-md-3" id="no_of_phases_div">
                        &nbsp;<div class="form-check">
                            <input type="checkbox" class="form-check-input" id="incPackagingPhase" name="incPackagingPhase" value="Yes" checked />
                            <label class="form-check-label" for="customCheck2">Include Packaging Phase</label>
                        </div>
                    </div>
                    <div class="col-md-3" id="product_category_div">
                        &nbsp;<div class="form-check">
                            <input type="checkbox" class="form-check-input" id="incLaborPhase" name="incLaborPhase" value="Yes" checked />
                            <label class="form-check-label" for="customCheck2">Include Labor Phase</label>
                        </div>
                    </div>
                    <div class="col-md-2 align-items-end">
                        <input type="hidden" name="draft_production_orders" value="{{$draft_production_orders}}">
                        <button type="submit" class="btn btn-success" name="action" value="store_cme_submit">Continue</button>
                    </div>
                </div>
                </form>
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

    <script>
        const inputField = document.getElementById('product_quantity_target');

        function formatNumber(){
            let inputValue = inputField.value;
            inputValue = inputValue.replace(/[^0-9]/g, '');
            inputValue = Number(inputValue).toLocaleString();
            inputField.value = inputValue;
        }

        inputField.addEventListener('input', formatNumber);
    </script>
@stop
