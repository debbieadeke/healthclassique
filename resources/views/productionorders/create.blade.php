@php(extract($data))
@extends('layouts.app-v2', ['pagetitle' => $pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Production Order</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('home') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                                <li class="breadcrumb-item" aria-current="page">Create</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{ asset('assets/images/breadcrumb/ChatBc.png') }}" alt=""
                                class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none position-relative overflow-hidden">

            <div class="card-body px-4 py-3">
                {{-- <form id="myForms" method="POST" action="{{ route('post-production-order') }}"> --}}
                    {{-- <form>
                    @csrf --}}
                    {{-- <div id="myForm"> --}}
                    <div class="content-form">
                        <div class="doctor-list-blks tbl" >
                            <div class="row p-2">
                                <div class="col-4">
                                    <label for="product_id"><b>Select Product </b></label>
                                    <select name="product_id" id="product_id" class="form-control mt-2">
                                        @foreach ($data['products'] as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label for="product_quantity_target"><b>Order Quantity</b></label>
                                    <input type="number" placeholder="100" name="product_quantity_target"
                                        id="product_quantity_target" value="10" class="form-control w-100 mt-2"
                                        required />
                                </div>
                                <div class="justify-content-end d-flex mt-3 w-100 align-items-center d-none">
                                    <div class="checkbox me-3">
                                        <label> <input type="checkbox" checked name="checkbox" /> Include Packaging Phase
                                        </label>
                                    </div>
                                    <div class="checkbox me-3">
                                        <label> <input type="checkbox" checked name="checkbox" /> Include Labor Phase
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="doctor-list-blk tbl" id="phase1">
                            <div class="chart-title">
                                <h4><b>PHASE A</b></h4>
                            </div>
                            <table class="table table-striped table-bordered mb-0" id="phase-1">
                                <thead>
                                    <tr>
                                        <th scope="col">Item Code/Batch</th>
                                        <th scope="col">Percent</th>
                                        <th scope="col">Grams to be added</th>
                                        <th scope="col">Ingredient <br> Cost</th>
                                        <th scope="col">Ingredient <br> Cost in Formula</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ingredient-entry">
                                        <th scope="row">
                                            <select name="input_id[]" id="inputId" class="form-control" onChange="getInputBatchPrice()">
                                                <option value="select_option" selected>Select Ingredient</option>
                                                @foreach ($data['ingredient_batches'] as $product)
                                                    @if ($product->input->type == 'ingredient')
                                                        <option value="{{ $product->input->id }}"                                                               data-extra-price="{{ $product->buying_price }}"
                                                                data-extra-stock="{{ $product->input->quantity_remaining }}">
                                                            {{ $product->input->code }}/{{ $product->batch_number }}
                                                            :: {{ $product->input->name }} -STH:
                                                            {{ number_format($product->quantity_remaining) }}
                                                    </option>
                                                    @endif
                                                @endforeach

                                            </select>
                                        </th>
                                        <td>
                                            <input type="text" id="percentage" class="form-control  percentageclass" name="percentage[]" onchange="calculateItemGrammage()" value="0" />
                                        </td>
                                        <td>
                                            <input type="text" id="grams_to_be_added" class="form-control gramstobeaddedclass" name="grams_to_be_added[]" placeholder="0" value="0" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control ingredientcostclass " readonly name="ingredient_cost[]" value="0"
                                                onchange="calculateItemGrammage()" />
                                        </td>
                                        <td>
                                            <input type="text" id="ingredient_cost_formula" class="form-control ingredientformulaclass" name="ingredient_cost_formula[]" value="0" />
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item remove-row" ><i class="fas fa-trash-alt" style="color:black; font-size: 12px;"></i> Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><a type="button" class="btn btn-primary submit-form me-2 add-ingredient">Add a Row</a></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- packaging Phase -->

                    <div class="border-bottom title-part-padding p-2">
                        <div class="chart-title">
                            <h4><b>Packaging Phase</b></h4>
                        </div>
                        <input type="hidden" name="phaseids[]" value="4">
                    </div>
                    {{-- <table class="table table-striped table-bordered mb-0" ids="phase-1"> --}}
                        <table class="table table-striped table-bordered mb-0" id="packaging">
                        <thead>
                            <tr>
                                <th scope="col">Description</th>
                                <th scope="col">Pack Weight (g)</th>
                                <th scope="col">Batch Qty</th>
                                <th scope="col">Unit Cost</th>
                                <th scope="col">Batch Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <select class="form-select selectinputclass" id="input_id" name="packaging_id[]" onchange="getInputBatchPrice()">
                                        <option value="select_option">Item</option>
                                        @foreach ($packaging_batches as $input_batch)
                                            @if ($input_batch->input->type == 'packaging')
                                                <option class="form-control" value="{{ $input_batch->input->id }}"
                                                    data-extra-price="{{ $input_batch->buying_price }}"
                                                    data-extra-qtydeterminant="{{ $input_batch->input->quantity_determinant }}">
                                                    {{ $input_batch->input->name }}
                                                    (In Stock:
                                                    {{ number_format($input_batch->quantity_remaining) }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </th>
                                <td>
                                    <input type="text" class="form-control packsizeclass" id="packsize" name="packsize[]" onfocusout="calculatePackagingBatchQty()" value="0" />
                                </td>
                                <td>
                                    <input type="text" class="form-control batchqtyclass" id="batchqty" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientcostclass" readonly name="ingredient_cost[]" value="0" onchange="calculatePackagingBatchQty()" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <select class="form-select selectinputclass" id="input_id" name="packaging_id[]"
                                        onchange="getInputBatchPrice()">
                                        <option value="select_option">Item</option>
                                        @foreach ($packaging_batches as $input_batch)
                                            @if ($input_batch->input->type == 'packaging')
                                                <option class="form-control" value="{{ $input_batch->input->id }}"
                                                    data-extra-price="{{ $input_batch->buying_price }}"
                                                    data-extra-qtydeterminant="{{ $input_batch->input->quantity_determinant }}">
                                                    {{ $input_batch->input->name }}
                                                    (In Stock:
                                                    {{ number_format($input_batch->quantity_remaining) }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </th>
                                <td>
                                    <input type="text" class="form-control packsizeclass" id="packsize" name="packsize[]" onfocusout="calculatePackagingBatchQty()" value="0" />
                                </td>
                                <td>
                                    <input type="text" class="form-control batchqtyclass" id="batchqty" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="0" onchange="calculatePackagingBatchQty()" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- end of packaging phase -->

                    <!-- miscelinees Phase -->
                    <div class="border-bottom title-part-padding p-2">
                        <h4 class="card-title mb-0">Labour & Other Misc. Costs</h4>
                        <input type="hidden" name="phaseids[]" value="6">
                    </div>
                    {{-- <table class="table table-striped table-bordered mb-0" ids="phase-1"> --}}
                        <table class="table table-striped table-bordered mb-0" id="labour">
                        <thead>
                            <tr>
                                <th scope="col">Description</th>
                                <th scope="col">Pack Weight (g)</th>
                                <th scope="col">Batch Qty</th>
                                <th scope="col">Unit Cost</th>
                                <th scope="col">Batch Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <select class="form-select selectinputclass" id="input_id" name="packaging_id[]"
                                        onchange="getInputBatchPrice()">
                                        <option value="select_option">Item</option>
                                        @foreach ($miscellaneous_batches as $ingredient_batch)
                                            @if ($ingredient_batch->input->type == 'miscellaneous')
                                                <option class="form-control" value="{{ $ingredient_batch->input->id }}"
                                                    data-extra-price="{{ $ingredient_batch->buying_price }}">
                                                    {{ $ingredient_batch->input->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </th>
                                <td>
                                    <input type="text" class="form-control packsizeclass" id="packsize" name="packsize[]" onchange="calculatePackagingBatchQty()" value="0" />
                                </td>
                                <td>
                                    <input type="text" class="form-control batchqtyclass" id="batchqty" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientcostclass" readonly name="ingredient_cost[]" value="0" onchange="calculatePackagingBatchQty()" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                </td>
                            </tr>
                            <tr>

                            </tr>
                            <tr>
                                <th scope="row">
                                    <select class="form-select selectinputclass" id="input_id" name="packaging_id[]"
                                        onchange="getInputBatchPrice()">
                                        <option value="select_option">Item</option>
                                        @foreach ($miscellaneous_batches as $ingredient_batch)
                                            @if ($ingredient_batch->input->type == 'miscellaneous')
                                                <option class="form-control" value="{{ $ingredient_batch->input->id }}"
                                                    data-extra-price="{{ $ingredient_batch->buying_price }}">
                                                    {{ $ingredient_batch->input->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </th>
                                <td>
                                    <input type="text" class="form-control packsizeclass" id="packsize" name="packsize[]" onchange="calculatePackagingBatchQty()" value="0" />
                                </td>
                                <td>
                                    <input type="text" class="form-control batchqtyclass" id="batchqty" name="batchqty[]" placeholder="0" value="0" style="background-color: #FA896B" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientcostclass" name="ingredient_cost[]" value="0" onchange="calculatePackagingBatchQty()" />
                                </td>
                                <td>
                                    <input type="text" class="form-control ingredientformulaclass" id="ingredient_cost_formula" name="ingredient_cost_formula[]" placeholder="0" />
                                </td>
                            <tr>
                        </tbody>
                    </table>
                    <!--end misleneous Phase -->
                    <div>
                        <div class="doctor-list-blk-below tbl">
                            <div class="justify-content-between d-flex mt-3 w-100 align-items-center pb-4">
                                <div class="d-flex justify-content-between">
                                    <h4 class="text-center">Total Batch Cost: <button disabled="disabled"
                                            class="btn btn-warning text-dark me-2 btn-lg" id="total_batch_cost">0</button>
                                    </h4>
                                    <h4 class="text-center">Total Batch Qty: <button disabled="disabled"
                                            class="btn btn-warning text-dark me-2 btn-lg" id="total_batch_quantity"
                                            value="0">0</button></h4>
                                    <h4 class="text-center">Actual Batch Qty: <button disabled="disabled"
                                            class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                                    <h4 class="text-center">Spoilt Qty(Pcs/Kgs): <button disabled="disabled"
                                            class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-primary text-white" id="add-new-phase">Add a Phase</button>
                                        <button type="submit" class="btn btn-info text-white  mx-1">Save Draft</button>
                                    </div>
                                    <button type="button" id="myForm" class="btn btn-success  mt-3 w-100 form-control">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
                {{-- </form> --}}
            </div>

        </div>
    </div>
    </div>


@endsection

@section('extra-scripts')
<script>
    $(document).ready(function() {
        // Event listener for the button
        $('.add-ingredient').click(function(e) {
            e.preventDefault();
            var originalRow = $('#phase-1 tbody tr.ingredient-entry').first();
            console.log(originalRow);
            var ingredientEntry = originalRow.clone();
            ingredientEntry.find('input').val('');
            $('#phase-1 tbody').append(ingredientEntry);
        });

        // Event listener for the "Remove" button
        $(document).on('click', '.remove-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });
    });
</script>
    <script>
        $(document).ready(function() {


            let selectDropdownData = <?php echo json_encode($data['ingredient_batches']); ?>;
            //data to loop from php
            var options = selectDropdownData.map(option => {
                let id = option['input']['id']
                let name = option['input']['name']
                return '<option value="' + id + '">' + name + '</option>';
            });

            //this block of code adds row to each phase

            $("#row-add").on("click", function() {
                addRow("phase-1");
            });
            //this block of code removes row to each phase

            $(document).on("click", ".removerowclass", function() {
                var confirmation = confirm("Are you sure you want to remove this row?");
                if (confirmation) {
                    removeRow(this);
                }

            });

            //rows to be displayed

            let row =
                '<td scope="row"><select name="input_id[]" id="" class="form-control">' +
                options +
                "</select></td>" +
                '<td><input type="text" class="form-control w-50 mx-auto percentageclass" name="percentage[]" onchange="calculateItemGrammage()" value="0"  /></td>' +
                '<td><input type="text" id="grams_to_be_added" class="form-control  gramstobeaddedclass w-50 mx-auto " name="grams_to_be_added[]" placeholder="0"  value="0"  /></td>' +
                '<td><input type="text" class="form-control  ingredientcostclass w-50 mx-auto " name="ingredient_cost[]"  value="0" onchange="calculateItemGrammage(event)"  /></td>' +
                '<td><input type="text" id="ingredient_cost_formula" class="form-control  ingredientformulaclass w-50 mx-auto " name="ingredient_cost_formula[]"  value="0"  /></td>';

            function addRow(phase) {

                var newRow =
                    "<tr>" +
                    row +
                    '<td>' +
                    '<button class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button" data-phase="' +
                    phase + '-remove">remove</button>' +
                    "</td>" +
                    "</tr>";

                $("#" + phase + " tbody").append(newRow);
            }

            //does the actual removal of row from a phase
            function removeRow(button) {
                var phase = $(button).data("phase").replace("-remove", "");
                $(button).closest("tr").remove();
                calculateItemGrammage();
            }



            // logic to add new phase

            $('#add-new-phase').on('click', function() {
                addPhase();
            })

            function addPhase() {
                var phaseCount = $('.doctor-list-blk').length + 1;
                console.log("phase count",$('.doctor-list-blk').length )
                var newPhaseHTML = '<div class="doctor-list-blk tbl" id="phase' + phaseCount + '">' +
                    '<div class="chart-title">' +
                    '<h4>PHASE ' + String.fromCharCode(65 + phaseCount - 1) + '</h4>' +
                    '</div>' +
                    '<table class="table table-striped table-bordered mb-0" id="phase-' + phaseCount + '">' +
                    '<thead>' +
                    '<tr>' +
                    '<th scope="col">Item Code/Batch</th>' +
                    '<th scope="col">Percent</th>' +
                    '<th scope="col">Grams to be added</th>' +
                    '<th scope="col">Ingredient Cost</th>' +
                    '<th scope="col">Ingredient Cost in Formula</th>' +
                    '<th scope="col">Action</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                    '<tr>' +
                    row +
                    '<td>' +
                    '<button class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass" type="button" data-phase="phase-' +
                    phaseCount + '-remove">remove</button>' +
                    '</td>' +
                    '</tr>' +
                    '</tbody>' +
                    '<tfoot>' +
                    '<tr>' +
                    '<td>' +
                    '<button type="button" class="btn btn-primary me-2" id="phase-' + phaseCount +
                    '-add">' +
                    'Add a Row' +
                    '</button>' +
                    '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-danger me-2 removePhaseClass" data-phase="phase-' +
                    phaseCount + '-remove">' +
                    'Remove Phase' +
                    '</button>' +
                    '</td>' +
                    '</tr>' +
                    '</tfoot>' +
                    '</table>' +
                    '</div>';

                // console.log(newPhaseHTML)

                $('.content-form').append(newPhaseHTML);

                // Add click event for new phase's "Add Row" button
                $('#phase-' + phaseCount + '-add').on('click', function() {
                    addRow('phase-' + phaseCount);
                });
            }
            //code to remove new phase
            $(document).on('click', '.removePhaseClass', function() {

                let phase = $(this).data('phase');
                var removalOne = phase.replace('-remove', '');
                var phaseName = removalOne.match(/phase/g);
                var removalTwo = removalOne.replace('phase', '');
                var PhaseNumber = removalTwo.replace('-', '');

                var phaseToRemove = phaseName[0] + PhaseNumber;
                $('#' + phaseToRemove).remove();
                calculateItemGrammage();

            });


            // Form submission handling
            $('#myForm').click(function(event) {
                //get product data details
               let prodId =  $('#product_id').val()
                let prodQnt = $('#product_quantity_target').val()
                //phases data
                // Organize data by phases
                var phasesData = {};
                $('.doctor-list-blk').each(function(index, element) {
                    var phaseId = $(element).attr('id');
                    // var phaseData = $(element).find('form').serializeArray();
                    var phaseData = $(element).find(':input').serializeArray();
                    phasesData[phaseId] = phaseData;
                });
                phasesData['_token'] = '{{ csrf_token() }}';

                //packaging data
                var packagingData = $('#packaging tbody').find(':input').serializeArray();
                // console.log("only packaging ",packagingData)

                //labour data
                var laborData = $('#labour tbody').find(':input').serializeArray();

                var formData = {
                        'phasesData': phasesData,
                        'packagingData': packagingData,
                        'laborData': laborData,
                        'prodId':prodId,
                        'prodQnt': prodQnt,
                        '_token': '{{ csrf_token() }}'
                    };


                $.ajax({
                    url: '{{ route("post-production-order") }}', // Replace this with your Laravel route
                    method: 'POST',
                    data: formData, // Form data to be sent
                    success: function(response) {
                        window.location.href = "{{ route('production-order.index')}}";
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors that occur during the AJAX request
                        console.error('Error sending form data:', error);
                    }
                });
            });

        });

        // function getIngredientCost(){

        //     var cost = $('#ingredient_cost_formula').val()
        //     console.log(cost);
        // }


        function calculateItemGrammage2(event) {

            // Get the batch quantity value

            var originalValue = document.getElementById("product_quantity_target").value;
            var newValue = originalValue.replace(/,/g, '');
            var prodQtyTarget = parseFloat(newValue);

            var totalIngredientFormula = 0; // To store the sum of ingredient formula values
            // Get all elements with class "percentageclass"
            var percentageInputs = document.querySelectorAll(".percentageclass");

            console.log("all classes = ", percentageInputs)

            percentageInputs.forEach(function(percentageInput, index) {
                // Get the percentage value
                var percentage = parseFloat(percentageInput.value);
                if (percentage > 0) {
                    // Calculate grams to be added
                    var gramsToBeAdded = (percentage * prodQtyTarget) / 100;
                    // console.log(gramsToBeAdded)

                    // Find the corresponding elements in the current row

                    var currentRow = percentageInput.closest("tr");
                    var gramsToBeAddedInput = currentRow.querySelector(".gramstobeaddedclass");
                    var ingredientCostInput = currentRow.querySelector(".ingredientcostclass");
                    var ingredientFormulaInput = currentRow.querySelector(".ingredientformulaclass");


                    // Update the values in the current row
                    gramsToBeAddedInput.value = gramsToBeAdded;
                    var ingredientCost = parseFloat(ingredientCostInput.value);
                    var ingredientFormula = gramsToBeAdded * ingredientCost;
                    ingredientFormulaInput.value = parseFloat(ingredientFormula).toFixed(2);
                    // Add to the total ingredient formula
                    totalIngredientFormula += ingredientFormula;
                }
            });
            // Update the sum of ingredient formula
            var totalBatchCostButton = document.querySelector('#total_batch_cost');
            totalBatchCostButton.innerText = totalIngredientFormula.toLocaleString();
        }

        function calculateItemGrammage() {
            //var prodQtyTarget = parseFloat(document.getElementById("product_quantity_target").value.replace(/,/g, '') || 0);

            var originalValue = document.getElementById("product_quantity_target").value;
            var newValue = originalValue.replace(/,/g, '');

            var prodQtyTarget = parseFloat(newValue);

            var totalIngredientFormula = 0;

            document.querySelectorAll(".percentageclass").forEach(function(percentageInput) {
                var percentage = parseFloat(percentageInput.value) || 0;
                var gramsToBeAdded = (percentage * prodQtyTarget) / 100;


                var currentRow = percentageInput.closest("tr");
                var gramsToBeAddedInput = currentRow.querySelector(".gramstobeaddedclass");
                var ingredientCostInput = currentRow.querySelector(".ingredientcostclass");

                gramsToBeAddedInput.value = gramsToBeAdded.toFixed(2);
                var ingredientCost = parseFloat(ingredientCostInput.value) || 0;
                var ingredientFormula = gramsToBeAdded * ingredientCost;

                var ingredientFormulaInput = currentRow.querySelector(".ingredientformulaclass");
                ingredientFormulaInput.value = ingredientFormula.toFixed(2);

                totalIngredientFormula += ingredientFormula;
            });

            document.getElementById("total_batch_cost").innerText = totalIngredientFormula.toFixed(2);
        }


        function getInputBatchPrice() {
            // Get the select element that triggered the change event
            var selectElement = event.target;
            // Get the selected option
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            // Get the data-extra-price attribute from the selected option
            var extraPrice = parseFloat(selectedOption.getAttribute("data-extra-price"));
            // Find the parent row of the select element
            var parentRow = selectElement.closest("tr");
            //  var currentRow = percentageInput.closest("tr");
            // Find the ingredient_cost input field within the same row
            var ingredientCostInput = parentRow.querySelector(".ingredientcostclass");
            // Set the ingredient_cost input field value to the selected ingredient's price
            ingredientCostInput.value = extraPrice;
        }

        function calculatePackagingBatchQty() {

            // Get the batch quantity value
            var batchQuantity = parseFloat(document.getElementById("product_quantity_target").value);
            // var runningSum = parseFloat(document.getElementById("total_batch_cost").value);

            var totalBatchCostButton = document.querySelector('#total_batch_cost');
            var runningSum = parseFloat(totalBatchCostButton.innerText)

            // Get all elements with class "packsizeclass"
            var packsizeInputs = document.querySelectorAll(".packsizeclass");


            var totalPackagingFormula = 0; // To store the sum of batchCost (packaging and labor)
            var totalBatchQnty = 0; //to store the sum of batchquantityvalues (packaging and labor)
            var totalPacksize = 0;



            // Loop through each percentage input
            packsizeInputs.forEach(function(packsizeInput, index) {
                // Get the packsize value
                var packsize = parseFloat(packsizeInput.value);
                totalPacksize += packsize
                // console.log(packsize)

                let total = document.getElementById("total_batch_quantity").value = packsizeInput.value;

                if (packsize > 0) {


                    // Calculate grams to be added
                    var batchQuantity2 = Math.round(batchQuantity / packsize);
                    // console.log(batchQuantity2)


                    // Find the corresponding elements in the current row
                    var currentRow = packsizeInput.closest("tr");
                    var batchQtyInput = currentRow.querySelector(".batchqtyclass");
                    var ingredientCostInput = currentRow.querySelector(".ingredientcostclass");
                    var ingredientFormulaInput = currentRow.querySelector(".ingredientformulaclass");
                    var inputSelect = currentRow.querySelector(".selectinputclass");

                    const selectedIndex = inputSelect.selectedIndex;
                    const selectedOption = inputSelect.options[selectedIndex];

                    const qtyDeterminant = selectedOption.getAttribute("data-extra-qtydeterminant");
                    var totalBatchCostButton = document.querySelector('#total_batch_quantity');

                    var qnty = 0;
                    if (qtyDeterminant === "Yes") {

                        var totalBatchCostButton = document.querySelector('#total_batch_quantity');
                        qnty = batchQuantity / packsize

                        totalBatchCostButton.innerText = batchQuantity / packsize
                    }
                    // console.log("total batch quantity = ", totalBatchCostButton.innerText)
                    // // Update the values in the current row
                    batchQtyInput.value = batchQuantity2;
                    var ingredientCost = parseFloat(ingredientCostInput.value);
                    var ingredientFormula = batchQuantity2 * ingredientCost;
                    ingredientFormulaInput.value = ingredientFormula;


                    // // Add to the total ingredient formula
                    totalPackagingFormula += ingredientFormula;
                    //get the total batch quantity for labor and packaging as well
                    totalBatchQnty += batchQtyInput.value
                    // console.log("prev qnty", qnty)
                    // console.log("qnty to add to ", totalBatchQnty)
                }
            });
            // console.log("total package weight:" + totalPacksize)
            // console.log("total batch cost (packaging and labor)" + totalPackagingFormula)
            console.log("total batch qnt = ", totalBatchQnty)

            // console.log("total :", runningSum + totalPackagingFormula)

            // Update the sum of ingredient formula
            // document.getElementById("total_batch_cost").value = runningSum + totalPackagingFormula;
            var totalBatchCostButton = document.querySelector('#total_batch_cost');
            totalBatchCostButton.innerText = Math.round(runningSum + totalPackagingFormula);

        }
    </script>



@stop
