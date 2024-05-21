$(function () {
    "use strict";

    // Default
    $(".repeater-default").repeater();

    // Custom Show / Hide Configurations
    $(".file-repeater, .email-repeater").repeater({
        show: function () {
            $(this).slideDown();
        },
        hide: function (remove) {
            if (confirm("Are you sure you want to remove this item?")) {
                $(this).slideUp(remove);
            }
        },
    });
});




var room = 1;

function batch_item_fields() {
    room++;
    var objTo = document.getElementById("batch_item_fields");
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "mb-1 removeclass" + room);
    var rdiv = "removeclass" + room;
    divtest.innerHTML =
        '<div class="row"><div class="col-3"> <div class="mb-1"> <select class="form-select" id="educationDate" name="educationDate"> <option>Input</option> <option value="2015">2015</option> <option value="2016">2016</option> <option value="2023">2023</option> <option value="2023">2023</option> </select> </div> </div> <div class="col-2"> <div class="mb-1"> <input type="number" class="form-control" id="percentage" name="percentage" placeholder="%" /> </div> </div> <div class="col-2"> <div class="mb-1"> <input type="number" class="form-control" id="grams_to_be_added" name="grams_to_be_added" placeholder="0" /> </div> </div> <div class="col-2"> <div class="mb-1"> <input type="number" class="form-control" id="ingredient_cost" name="ingredient_cost" placeholder="0" /> </div> </div> <div class="col-2"> <div class="mb-1"> <input type="number" class="form-control" id="ingredient_cost_formula" name="ingredient_cost_formula" placeholder="0" /> </div> </div><div class="col-1"> <div class="form-group"> <button class="btn btn-danger" type="button" onclick="remove_batch_item_fields(' +
        room +
        ');"> <i class="ti ti-minus"></i> </button> </div></div></div>';

    objTo.appendChild(divtest);
}

function clone_batch_item_fields(i) {
    room++;
    var objTo = document.getElementById("batch_item_fields"+i);
    // Select the original div and clone its content
    var originalContent = $("#originalDiv").clone();

    var divtest = document.createElement("div");
    divtest.setAttribute("class", "mb-1 removeclass" + room);
    var rdiv = "removeclass" + room;

    var childIndexToEmpty = 5; // For Child DIV 6
    // Select the specific child div by index and empty its contents
    $("#parentDiv .childDiv:eq(" + childIndexToEmpty + ")").empty();
    // Empty the content of the third childDiv
    originalContent.find(".childDiv:nth-child(6)").empty();


    // Empty the copied div and append the cloned content
    $("#batch_item_fields").append(originalContent);

    objTo.appendChild(divtest);
}

function remove_batch_item_fields(rid) {
    $(".removeclass" + rid).remove();
}
