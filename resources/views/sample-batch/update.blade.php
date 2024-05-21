@php(extract($data))
@extends('layouts.app-narrow',['pagetitle'=>$pagetitle])

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
        <form action="{{route('production-order.store')}}" method="post" id="myForm">
            @csrf

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-1 py-1">

                <div class="row">
                    <div class="col-md-4">
                        <!-- Double Call (Drop-down) -->
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Selected Product</label>
                            <select class="form-control select2" style="width: 100%; height: 40px" id="product_id" name="product_id" onchange="updateProdBatchDetails()">
                                @foreach($product_settings as $product_phase)
                                    @if ($product_phase->id == $product_setting_id)
                                        <option selected class="form-control" value="{{$product_phase->id}}" data-extra-no_of_phases="{{$product_phase->no_of_phases}}" data-extra-category="Ointment">{{$product_phase->product->name}}</option>
                                    @else
                                        <option class="form-control" value="{{$product_phase->id}}" data-extra-no_of_phases="{{$product_phase->no_of_phases}}" data-extra-category="Ointment">{{$product_phase->product->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" id="no_of_phases_div">
                        <label for="no_of_phases" class="form-label">No of Phases</label>
                        <input type="text" class="form-control" id="no_of_phases" name="no_of_phases" readonly value="{{$no_of_phases}}">
                    </div>
                    <div class="col-md-2" id="rows_per_phase_div">
                        <label for="rows_per_phase" class="form-label">Rows / Phase</label>
                        <input type="text" class="form-control" id="rows_per_phase" name="rows_per_phase" readonly value="{{$rows_per_phase}}">
                    </div>
                    <div class="col-md-2" id="product_category_div">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" readonly value="{{$category}}">
                    </div>
                    <div class="col-md-2">
                        <label for="product_quantity_target" class="form-label">Order Quantity</label>
                        <input type="text" class="form-control" id="product_quantity_target" name="product_quantity_target" value="{{$product_quantity_target}}">
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-6">
                        &nbsp;
                    </div>
                    <div class="col-md-2" id="no_of_phases_div">
                        &nbsp;
                    </div>
                    <div class="col-md-2" id="product_category_div">
                        &nbsp;
                    </div>
                    <div class="col-md-2 align-items-end">
                        <a class="btn btn-danger" href="{{route('production-order.create')}}" role="button">Restart Process</a>
                    </div>
                    </div>
            </div>
        </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="card">

                        @for ($i = 1; $i <= $no_of_phases; $i++)
                            <div class="border-bottom title-part-padding p-2">
                                <h4 class="card-title mb-0">{{$phases[$i-1]->name}}</h4>
                                <input type="hidden" name="phaseids[]" value="{{$phases[$i-1]->id}}">
                            </div>
                            <div class="card-body">
                                <div class="row border border-dark">
                                    <div class="col-4 border border-dark-subtle">
                                        <div class="mb-1">
                                            <p class="h6">Item Code / Batch</p>
                                        </div>
                                    </div>
                                    <div class="col-1 border border-dark-subtle">
                                        <div class="mb-1">
                                            <p class="h6">Percent</p>
                                        </div>
                                    </div>
                                    <div class="col-2 border border-dark-subtle">
                                        <div class="mb-1">
                                            <p class="h6 text-danger">Grams to be added</p>
                                        </div>
                                    </div>
                                    <div class="col-2 border border-dark-subtle">
                                        <div class="mb-1">
                                            <p class="h6">Ingredient cost / g</p>
                                        </div>
                                    </div>
                                    <div class="col-3 border border-dark-subtle text-nowrap">
                                        <div class="mb-1">
                                            <p class="h6">Ingredient cost in formula</p>
                                        </div>
                                    </div>
                                </div>
                                @for ($x = 1; $x <= $rows_per_phase; $x++)
                                    <div class="row border border-dark" id="originalDiv{{$i}}">
                                        <div class="col-4 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <select class="form-select selectingredientclass" id="input_id" name="input_id[]" onchange="getInputBatchPrice()">
                                                    <option value="select_option">Ingredient</option>
                                                    @foreach($ingredient_batches as $ingredient_batch)
                                                        @if ($ingredient_batch->input->type == "ingredient")
                                                            <option class="form-control" value="{{$ingredient_batch->input->id}}_{{$i}}" data-extra-price="{{$ingredient_batch->buying_price}}" data-extra-stock="{{$ingredient_batch->input->quantity_remaining}}">{{$ingredient_batch->input->code}}/{{$ingredient_batch->batch_number}} :: {{$ingredient_batch->input->name}} -STH: {{number_format($ingredient_batch->quantity_remaining)}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control percentageclass" id="percentage{{$i}}" name="percentage[]" onchange="calculateItemGrammage()" value="0"  />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control gramstobeaddedclass" id="grams_to_be_added{{$i}}" name="grams_to_be_added[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="0" onchange="calculateItemGrammage()" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <button onclick="removeRow()" class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button">
                                                    <i class="ti ti-circle-minus fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <div id="batch_item_fields{{$i}}" class="my-1"></div>
                            </div>
                        @endfor

                        @if ($incPackagingPhase == "Yes")
                                <div class="border-bottom title-part-padding p-2">
                                    <h4 class="card-title mb-0">Packaging Phase</h4>
                                    <input type="hidden" name="phaseids[]" value="4">
                                </div>
                                <div class="card-body">
                                    <div class="row border border-dark">
                                        <div class="col-4 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Description</p>
                                            </div>
                                        </div>
                                        <div class="col-2 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Pack Weight (g)</p>
                                            </div>
                                        </div>
                                        <div class="col-1 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6 text-danger">Batch Qty</p>
                                            </div>
                                        </div>
                                        <div class="col-2 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Unit Cost</p>
                                            </div>
                                        </div>
                                        <div class="col-3 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Batch Cost</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row border border-dark" id="originalDiv{{$i}}">
                                        <div class="col-4 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <select class="form-select selectinputclass" id="input_id" name="packaging_id[]" onchange="getInputBatchPrice()">
                                                    <option value="select_option">Item</option>
                                                    @foreach($packaging_batches as $input_batch)
                                                        @if ($input_batch->input->type == "packaging")
                                                            <option class="form-control" value="{{$input_batch->input->id}}_{{$i+1}}" data-extra-price="{{$input_batch->buying_price}}" data-extra-qtydeterminant="{{$input_batch->input->quantity_determinant}}">{{$input_batch->input->name}} (In Stock: {{number_format($input_batch->quantity_remaining)}})</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control packsizeclass" id="packsize{{$i}}" name="packsize[]"  onfocusout="calculatePackagingBatchQty()" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control batchqtyclass" id="batchqty{{$i}}" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="100" onchange="calculatePackagingBatchQty()" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <button onclick="removeRow()" class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button">
                                                    <i class="ti ti-circle-minus fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row border border-dark" id="originalDiv{{$i}}">
                                        <div class="col-4 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <select class="form-select selectinputclass" id="input_id" name="packaging_id[]" onchange="getInputBatchPrice()">
                                                    <option value="select_option">Item</option>
                                                    @foreach($packaging_batches as $ingredient_batch)
                                                        @if ($ingredient_batch->input->type == "packaging")
                                                            <option class="form-control" value="{{$ingredient_batch->input->id}}_{{$i+2}}" data-extra-price="{{$ingredient_batch->buying_price}}" data-extra-qtydeterminant="{{$ingredient_batch->input->quantity_determinant}}">{{$ingredient_batch->input->name}} (In Stock: {{number_format($ingredient_batch->quantity_remaining)}})</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control packsizeclass" id="packsize{{$i}}" name="packsize[]" onchange="calculatePackagingBatchQty()" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control batchqtyclass" id="batchqty{{$i}}" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="100" onchange="calculatePackagingBatchQty()" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <button onclick="removeRow()" class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button">
                                                    <i class="ti ti-circle-minus fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endif

                        @if ($incLaborPhase == "Yes")
                                <div class="border-bottom title-part-padding p-2">
                                    <h4 class="card-title mb-0">Labour & Other Misc. Costs</h4>
                                    <input type="hidden" name="phaseids[]" value="6">
                                </div>
                                <div class="card-body">
                                    <div class="row border border-dark">
                                        <div class="col-4 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Description</p>
                                            </div>
                                        </div>
                                        <div class="col-2 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Pack Weight (g)</p>
                                            </div>
                                        </div>
                                        <div class="col-1 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6 text-danger">Batch Qty</p>
                                            </div>
                                        </div>
                                        <div class="col-2 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Unit Cost</p>
                                            </div>
                                        </div>
                                        <div class="col-3 border border-dark-subtle">
                                            <div class="mb-1">
                                                <p class="h6">Batch Cost</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row border border-dark" id="originalDiv{{$i}}">
                                        <div class="col-4 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <select class="form-select selectinputclass" id="input_id" name="packaging_id[]" onchange="getInputBatchPrice()">
                                                    <option value="select_option">Item</option>
                                                    @foreach($miscellaneous_batches as $ingredient_batch)
                                                        @if ($ingredient_batch->input->type == "miscellaneous")
                                                            <option class="form-control" value="{{$ingredient_batch->input->id}}_{{$i+3}}" data-extra-price="{{$ingredient_batch->buying_price}}">{{$ingredient_batch->input->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control packsizeclass" id="packsize{{$i}}" name="packsize[]" onchange="calculatePackagingBatchQty()" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control batchqtyclass" id="batchqty{{$i}}" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="100" onchange="calculatePackagingBatchQty()" />
                                            </div>
                                        </div>
                                        <div class="col-2 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                            </div>
                                        </div>
                                        <div class="col-1 childDiv border border-dark-subtle">
                                            <div class="mb-1">
                                                <button onclick="removeRow()" class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button">
                                                    <i class="ti ti-circle-minus fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <h4 class="card-title mb-0"><label for="total_batch_cost" class="form-label">Total Cost</label></h4>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="total_batch_cost" name="total_batch_cost" value="" readonly>
                </div>
                <div class="col-md-2">
                    <h4 class="card-title mb-0"><label for="total_batch_quantity" class="form-label text-end">Total Batch Quantities</label></h4>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="total_batch_quantity" name="total_batch_quantity" value="0" readonly>
                </div>
                <div class="col-md-1">
                    <input type="hidden" name="production_setting_id" value="{{$product_setting_id}}">
                    <input type="hidden" name="production_quantity_target" value="{{$product_quantity_target}}">
                    <input type="hidden" name="rows_per_phase" value="{{$rows_per_phase}}">
                    <input type="hidden" name="total_no_of_phases" value="{{$total_no_of_phases}}">
                    <input type="hidden" name="packaging_phase" value="{{$incPackagingPhase}}">
                    <input type="hidden" name="labour_phase" value="{{$incLaborPhase}}">
                </div>

                <div class="col-md-4 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" name="save_draft" class="btn btn-warning save-draft-btn" value="1">Save Draft</button>
                    <a href="{{route('production-order.create')}}" class="btn btn-danger" role="button" aria-disabled="true">Cancel Order Process</a>
                    <button type="submit" class="btn btn-success" name="action" value="order_submit">Submit Order</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10">
                </div>
                <div class="col-md-2">

                </div>
            </div>
        </form>
    </div>







@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
    <script>
        function updateProdBatchDetails() {
            const selectElement = document.getElementById("product_id");
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

        function calculateItemGrammage() {
            // Get the batch quantity value

			var originalValue = document.getElementById("product_quantity_target").value;
			var newValue = originalValue.replace(/,/g, '');

            var prodQtyTarget = parseFloat(newValue);

            // Get all elements with class "percentageclass"
            var percentageInputs = document.querySelectorAll(".percentageclass");

            var totalIngredientFormula = 0; // To store the sum of ingredient formula values

            // Loop through each percentage input
            percentageInputs.forEach(function (percentageInput, index) {
                // Get the percentage value
                var percentage = parseFloat(percentageInput.value);

                if (percentage > 0) {
                    // Calculate grams to be added
                    var gramsToBeAdded = (percentage * prodQtyTarget) / 100;

                    // Find the corresponding elements in the current row
                    var currentRow = percentageInput.closest(".row");
                    var gramsToBeAddedInput = currentRow.querySelector(".gramstobeaddedclass");
                    var ingredientCostInput = currentRow.querySelector(".ingredientcostclass");
                    var ingredientFormulaInput = currentRow.querySelector(".ingredientformulaclass");

                    // Update the values in the current row
                    gramsToBeAddedInput.value = gramsToBeAdded;
                    var ingredientCost = parseFloat(ingredientCostInput.value);
                    var ingredientFormula = gramsToBeAdded * ingredientCost;
                    ingredientFormulaInput.value = parseFloat(ingredientFormula).toFixed(2);;

                    // Add to the total ingredient formula
                    totalIngredientFormula += ingredientFormula;
                }
            });

            // Update the sum of ingredient formula
            document.getElementById("total_batch_cost").value = totalIngredientFormula;
        }

        function calculatePackagingBatchQty() {

            // Get the batch quantity value
            var batchQuantity = parseFloat(document.getElementById("product_quantity_target").value);
            var runningSum = parseFloat(document.getElementById("total_batch_cost").value);

            // Get all elements with class "packsizeclass"
            var packsizeInputs = document.querySelectorAll(".packsizeclass");

            var totalPackagingFormula = 0; // To store the sum of ingredient formula values

            // Loop through each percentage input
            packsizeInputs.forEach(function (packsizeInput, index) {
                // Get the packsize value
                var packsize = parseFloat(packsizeInput.value);

                //document.getElementById("total_batch_quantity").value = packsizeInput.value;

                if (packsize > 0) {
                    // Calculate grams to be added
                    var batchQuantity2 = (batchQuantity / packsize);

                    // Find the corresponding elements in the current row
                    var currentRow = packsizeInput.closest(".row");
                    var batchQtyInput = currentRow.querySelector(".batchqtyclass");
                    var ingredientCostInput = currentRow.querySelector(".ingredientcostclass");
                    var ingredientFormulaInput = currentRow.querySelector(".ingredientformulaclass");
                    var inputSelect = currentRow.querySelector(".selectinputclass");

                    const selectedIndex = inputSelect.selectedIndex;
                    const selectedOption = inputSelect.options[selectedIndex];
                    const qtyDeterminant = selectedOption.getAttribute("data-extra-qtydeterminant");

                    if (qtyDeterminant === "yes") {
                        document.getElementById("total_batch_quantity").value = batchQuantity / packsize
                    }

                    // Update the values in the current row
                    batchQtyInput.value = batchQuantity2;
                    var ingredientCost = parseFloat(ingredientCostInput.value);
                    var ingredientFormula = batchQuantity2 * ingredientCost;
                    ingredientFormulaInput.value = ingredientFormula;

                    // Add to the total ingredient formula
                    totalPackagingFormula += ingredientFormula;
                }
            });

            // Update the sum of ingredient formula
            document.getElementById("total_batch_cost").value = runningSum + totalPackagingFormula;
        }
    </script>

	<script>
		function removeRow() {
			var confirmation = confirm("Are you sure you want to remove this row?");

			if (confirmation) {
				var rowToRemove = event.target.closest(".row");
				if (rowToRemove) {
					rowToRemove.remove();
					calculateItemGrammage(); // Recalculate after removing a row
				}
			}
		}

        function getInputBatchPrice() {
            // Get the select element that triggered the change event
            var selectElement = event.target;

            // Get the selected option
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            // Get the data-extra-price attribute from the selected option
            var extraPrice = parseFloat(selectedOption.getAttribute("data-extra-price"));

            // Find the parent row of the select element
            var parentRow = selectElement.closest(".row");

            // Find the ingredient_cost input field within the same row
            var ingredientCostInput = parentRow.querySelector(".ingredientcostclass");

            // Set the ingredient_cost input field value to the selected ingredient's price
            ingredientCostInput.value = extraPrice;
        }

		const inputField = document.getElementById('total_batch_cost');

        function formatNumber(){
            let inputValue = inputField.value;
            inputValue = inputValue.replace(/[^0-9]/g, '');
            inputValue = Number(inputValue).toLocaleString();
            inputField.value = inputValue;
        }

        inputField.addEventListener('input', formatNumber);
    </script>

@stop
