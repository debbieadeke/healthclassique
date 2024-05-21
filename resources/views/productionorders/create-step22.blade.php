<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css" />
    <!-- <link rel="stylesheet" href="assets/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>




    <div class="col-md-8 mx-auto">
        <div class="main-wrapper">
            <div class="header">
                <div class="header-left">
                    <a href="index.html" class="logo">
                        <img src="assets/img/logo.png" height="44" alt="" />
                    </a>
                </div>
                <a id="toggle_btn" href="javascript:void(0);"><img src="assets/img/icons/bar-icon.svg"
                        alt="" /></a>
                <a id="mobile_btn" class="mobile_btn float-start" href="#sidebar"><img
                        src="assets/img/icons/bar-icon.svg" alt="" /></a>
                <div class="top-nav-search mob-view">
                    <form>
                        <input type="text" class="form-control" placeholder="Search here" />
                        <a class="btn"><img src="assets/img/icons/search-normal.svg" alt="" /></a>
                    </form>
                </div>
            </div>
            <div class="page-wrapper">
                <div class="content">
                    <div class="doctor-list-blk tbl">
                        <div class="row">
                            <div class="col-4">
                                <label for="">Select Product </label>
                                <input list="brow" class="form-control mt-2" />
                                <datalist id="brow">
                                    <option value="Internet Explorer"> </option>
                                    <option value="Firefox"> </option>
                                    <option value="Chrome"> </option>
                                    <option value="Opera"> </option>
                                    <option value="Safari"> </option>
                                </datalist>
                            </div>
                            <div class="col-2">
                                <label for="">Batch</label>
                                <input type="number" placeholder="89" name="" id=""
                                    class="form-control w-100 mt-2" />
                            </div>
                            <div class="col-2">
                                <label for="">Category</label>
                                <input type="number" placeholder="89" name="" id=""
                                    class="form-control w-100 mt-2" />
                            </div>
                            <div class="col-2">
                                <label for="">Order Quantity</label>
                                <input type="number" placeholder="8,009,079" name="" id=""
                                    class="form-control w-100 mt-2" />
                            </div>
                            <div class="col-2 align-self-end">
                                <button type="submit"
                                    class="btn btn-success submit-form me-2 w-100 form-control">Continue</button>
                            </div>
                            <div class="justify-content-end d-flex mt-3 w-100 align-items-center">
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
                    <div class="doctor-list-blk tbl">
                        <div class="chart-title">
                            <h4>PHASE A</h4>
                        </div>
                        <table class="table table-striped table-bordered mb-0" id="phase-1">
                            <thead>
                                <tr>
                                    <th scope="col">Item Code/Batch</th>
                                    <th scope="col">Percent</th>
                                    <th scope="col">Grams to be added</th>
                                    <th scope="col">Ingredient Cost</th>
                                    <th scope="col">Ingredient Cost in Formula</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <select name="" id="" class="form-control">
                                            <option value="">Customer Calls By Class </option>
                                            <option value="">Test</option>
                                            <option value="">Test</option>
                                        </select>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control percentageclass" name="percentage[]"
                                            onchange="calculateItemGrammage()" value="0"
                                            class="form-control w-50 mx-auto" />

                                    </td>
                                    <td>40</td>
                                    <td><input type="number" placeholder="10.09" name="" id=""
                                            class="form-control w-50 mx-auto" /></td>
                                    <td><input type="number" placeholder="10.09" name="" id=""
                                            class="form-control w-50 mx-auto" /></td>
                                    <td>
                                        <button
                                            class="btn btn-warning font-weight-medium waves-effect waves-light removerowclass"
                                            type="button" id="row-one-remove">
                                            remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><button type="submit" class="btn btn-primary submit-form me-2"
                                            id="row-add">Add a Row</button></td>

                                    <td><button type="submit"
                                            class="btn btn-danger submit-form me-2 removePhaseClass"
                                            id="phase-one-remove">Remove Phase</button></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
                <div>
                    <div class="doctor-list-blk-below tbl">
                        <div class="justify-content-between d-flex mt-3 w-100 align-items-center pb-4">
                            <div class="d-flex justify-content-between">
                                <h4 class="text-center">Total Batch Cost: <button disabled="disabled"
                                        class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                                <h4 class="text-center">Total Batch Qty: <button disabled="disabled"
                                        class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                                <h4 class="text-center">Actual Batch Qty: <button disabled="disabled"
                                        class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                                <h4 class="text-center">Spoilt Qty(Pcs/Kgs): <button disabled="disabled"
                                        class="btn btn-warning text-dark me-2 btn-lg">4,909,767</button></h4>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-primary text-white submit-form" id="add-new-phase">Add a
                                        Phase</button>
                                    <button type="submit" class="btn btn-info text-white submit-form mx-1">Save
                                        Draft</button>
                                </div>
                                <button type="submit"
                                    class="btn btn-success submit-form mt-3 w-100 form-control">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

        let selectDropdownData = ["option one", "option two", 'option three',
            'data four'
        ]; //data to loop from php
        var options = selectDropdownData.map(option => {
            return '<option value="' + option + '">' + option + '</option>';
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
            '<th scope="row"><select name="" id="" class="form-control">' +
            options +
            "</select></th>" +
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
        }



        // logic to add new phase

        $('#add-new-phase').on('click', function() {
            addPhase();
        })

        function addPhase() {
            var phaseCount = $('.doctor-list-blk').length;
            // console.log($('.doctor-list-blk').length )
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
                '<button type="button" class="btn btn-primary submit-form me-2" id="phase-' + phaseCount +
                '-add">' +
                'Add a Row' +
                '</button>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger submit-form me-2 removePhaseClass" data-phase="phase-' +
                phaseCount + '-remove">' +
                'Remove Phase' +
                '</button>' +
                '</td>' +
                '</tr>' +
                '</tfoot>' +
                '</table>' +
                '</div>';

            $('.content').append(newPhaseHTML);

            // Add click event for new phase's "Add Row" button
            $('#phase-' + phaseCount + '-add').on('click', function() {
                addRow('phase-' + phaseCount);
            });
        }
        //code to remove new phase
        $(document).on('click', '.removePhaseClass', function() {

            let phase = $(this).data('phase');
            console.log(phase)
            var removalOne = phase.replace('-remove', '');
            var phaseName = removalOne.match(/phase/g);
            var removalTwo = removalOne.replace('phase', '');
            var PhaseNumber = removalTwo.replace('-', '');

            var phaseToRemove = phaseName[0] + PhaseNumber;
            $('#' + phaseToRemove).remove();

        });

    });


    function calculateItemGrammage(event) {

        // Get the batch quantity value

        // var originalValue = document.getElementById("product_quantity_target").value;
        // var newValue = originalValue.replace(/,/g, '');
        // var prodQtyTarget = parseFloat(newValue);

        let prodQtyTarget = 10 //used for testing purpose .replace with the above commented code after intergration

        // Get all elements with class "percentageclass"
        var percentageInputs = document.querySelectorAll(".percentageclass");
        percentageInputs.forEach(function(percentageInput, index) {
            console.log(percentageInput.value)
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

    }
</script>


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
        percentageInputs.forEach(function(percentageInput, index) {
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
        packsizeInputs.forEach(function(packsizeInput, index) {
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
