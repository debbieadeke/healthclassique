<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets-v2/img/favicon.png') }}">
    <title></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-v2/plugins/fontawesome/css/all.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/select2.min.css') }}">

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/plugins/datatables/datatables.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/css/feather.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/style.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/custom.css') }}">
</head>


<body>
    <div class="content">

        @yield('content-v2')

    </div>


<!-- jQuery -->
<script src="{{ asset('assets-v2/js/jquery-3.7.1.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets-v2/js/bootstrap.bundle.min.js') }}"></script>

<!-- Feather Js -->
<script src="{{ asset('assets-v2/js/feather.min.js') }}"></script>

<!-- Slimscroll -->
<script src="{{ asset('assets-v2/js/jquery.slimscroll.js') }}"></script>

<!-- Select2 Js -->
<script src="{{ asset('assets-v2/js/select2.min.js') }}"></script>

<!-- Datatables JS -->
<script src="{{ asset('assets-v2/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets-v2/plugins/datatables/datatables.min.js') }}"></script>

<!-- counterup JS -->
<script src="{{ asset('assets-v2/js/jquery.waypoints.js') }}"></script>
<script src="{{ asset('assets-v2/js/jquery.counterup.min.js') }}"></script>

<!-- Apexchart JS -->
<script src="{{ asset('assets-v2/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets-v2/plugins/apexchart/chart-data.js') }}"></script>

<!-- Circle Progress JS -->
<script src="{{ asset('assets-v2/js/circle-progress.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets-v2/js/app.js') }}"></script>



  <script>
    function getUserLocation() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);
      } else {
          alert("Geolocation is not supported by this browser.");
      }
    }

	function getUserDetails() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);
      } else {
          alert("Geolocation is not supported by this browser.");
      }
        var AddDoctorRowDiv = document.getElementById("AddDoctorRow");
		const selectElement = document.getElementById("client_id");

        if (selectElement.value == "add_new") {
            AddDoctorRowDiv.style.display = "flex";
            document.getElementById("speciality_div").style.display = "none";
            document.getElementById("class_div").style.display = "none";
        } else {
            var inputs = AddDoctorRowDiv.querySelectorAll('input');
            var selects = AddDoctorRowDiv.querySelectorAll('select');

            inputs.forEach(function(input) {
                input.value = '';
            });

            selects.forEach(function(select) {
                select.selectedIndex = 0;
            });

            AddDoctorRowDiv.style.display = "none";

            document.getElementById("speciality_div").style.display = "flex";
            document.getElementById("class_div").style.display = "flex";


            const selectedIndex = selectElement.selectedIndex;
            const selectedOption = selectElement.options[selectedIndex];

            const extraInfo = selectedOption.getAttribute("data-extra-info");
            const inputElement = document.getElementById("speciality");

            const classInfo = selectedOption.getAttribute("data-extra-class");
            const inputElement2 = document.getElementById("myclass");

            // Check if the element is found
            if (inputElement) {
                // Set the value of the input field
                inputElement.value = extraInfo;
            }

            // Check if the element is found
            if (inputElement2) {
                // Set the value of the input field
                inputElement2.value = classInfo;
            }
        }
    }

	function getLimitedUserDetails() {

		const selectElement = document.getElementById("client_id");

		const selectedIndex = selectElement.selectedIndex;
		const selectedOption = selectElement.options[selectedIndex];

		const extraInfo = selectedOption.getAttribute("data-extra-info");
		const inputElement = document.getElementById("speciality");

		const classInfo = selectedOption.getAttribute("data-extra-class");
		const inputElement2 = document.getElementById("myclass");


		// Check if the element is found
		if (inputElement) {
			// Set the value of the input field
			inputElement.value = extraInfo;
		}

		// Check if the element is found
		if (inputElement2) {
			// Set the value of the input field
			inputElement2.value = classInfo;
		}

    }

    function getLimitedUserDetails2() {

        const selectElement = document.getElementById("facility_id");

        const selectedIndex = selectElement.selectedIndex;
        const selectedOption = selectElement.options[selectedIndex];

        const classInfo = selectedOption.getAttribute("data-extra-class");
        const inputElement2 = document.getElementById("myclass");


        // Check if the element is found
        if (inputElement2) {
            // Set the value of the input field
            inputElement2.value = classInfo;
        }

    }

	function getFacilityDetails() {
		const selectElement = document.getElementById("client_id");
		if (selectElement.value === "add_new") {
            AddFacilityRowDiv.style.display = "flex";
            document.getElementById("speciality_div").style.display = "none";
            document.getElementById("class_div").style.display = "none";
        } else {
            const selectedIndex = selectElement.selectedIndex;
		    const selectedOption = selectElement.options[selectedIndex];
            const classInfo = selectedOption.getAttribute("data-extra-class");
		    const inputElement2 = document.getElementById("myclass");
            if (inputElement2) {
			    inputElement2.value = classInfo;
		    }
            AddFacilityRowDiv.style.display = "none";
		}
	}

	function displayAddNewRow() {
		const selectElement = document.getElementById("client_id");
		var AddFacilityRowDiv = document.getElementById("AddFacilityRow");
		//window.alert(AddFacilityRowDiv.style.display);
		if (selectElement.value === "add_new") {
            AddFacilityRowDiv.style.display = "flex";
            //document.getElementById("speciality_div").style.display = "none";
            document.getElementById("class_div").style.display = "none";
        } else {
            const inputElement2 = document.getElementById("myclass");
            const selectedIndex = selectElement.selectedIndex;
            const selectedOption = selectElement.options[selectedIndex];
            const classInfo = selectedOption.getAttribute("data-extra-class");
            if (inputElement2) {
                inputElement2.value = classInfo;
            }
			AddFacilityRowDiv.style.display = "none";
		}

	}

    function showPosition(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      document.getElementById("longitude").value = longitude;
      document.getElementById("latitude").value = latitude;
    }
  </script>


  <script>
    // JavaScript approach
    document.getElementById("addPharmtechRow").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the default link behavior

        // Get the container element
        var container = document.getElementById("pharmtechRowsContainer");

        // Clone the "PharmTechRow" div
        var clone = container.firstElementChild.cloneNode(true);

		// Find all input fields and textareas within the cloned row
        var clonedInputFields = clone.querySelectorAll("input");
        var clonedTextareas = clone.querySelectorAll("textarea");

        // Reset the values of input fields and textareas
        clonedInputFields.forEach(function (input) {
            input.value = "";
        });

        clonedTextareas.forEach(function (textarea) {
            textarea.value = "";
        });

        // Append the clone to the container
        container.appendChild(clone);
    });

	// JavaScript approach
    document.getElementById("addDoctorRow").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent the default link behavior

        // Get the container element
        var container = document.getElementById("doctorRowsContainer");

        // Clone the "PharmTechRow" div
        var clone = container.firstElementChild.cloneNode(true);

		// Find all input fields and textareas within the cloned row
        var clonedInputFields = clone.querySelectorAll("input");
        var clonedTextareas = clone.querySelectorAll("textarea");

        // Reset the values of input fields and textareas
        clonedInputFields.forEach(function (input) {
            input.value = "";
        });

        clonedTextareas.forEach(function (textarea) {
            textarea.value = "";
        });

        // Append the clone to the container
        container.appendChild(clone);
    });
</script>

<script>
	function processOrderBooking() {
		var orderBookedSelect = document.getElementById("order_booked");
		var orderBookNoDiv = document.getElementById("OrderBookNo");
		var orderBookYesDiv = document.getElementById("OrderBookYes");

		// Hide both divs by default
		orderBookNoDiv.style.display = "none";
		orderBookYesDiv.style.display = "none";

		var selectedValue = orderBookedSelect.value;

		if (selectedValue === "Yes") {
			// Show the OrderBookYes div
			orderBookYesDiv.style.display = "block";
		} else if (selectedValue === "No") {
			// Show the OrderBookNo div
			orderBookNoDiv.style.display = "block";
		}
	}

    function processSamplesGiven() {
        var sampleSelect1 = document.getElementById("product_id");
        var sampleSelect2 = document.getElementById("product_id2");
        var sampleSelect3 = document.getElementById("product_id3");

        // Hide both divs by default
        SampleGivenYes.style.display = "none";


        if ((sampleSelect1.value !== "") || (sampleSelect2.value !== "") || (sampleSelect3.value !== "")) {
            // Show the SampleGivenYes div
            SampleGivenYes.style.display = "block";
        } else {
            // Don't show the SampleGivenYes div
            SampleGivenYes.style.display = "none";
        }
    }

	//processPrescriptionAudit
	function processPrescriptionAudit() {
		var orderBookedSelect = document.getElementById("prescription_audited");
		var orderBookNoDiv = document.getElementById("PrescriptionAuditNo");
		var orderBookYesDiv = document.getElementById("PrescriptionAuditYes");

		// Hide both divs by default
		orderBookNoDiv.style.display = "none";
		orderBookYesDiv.style.display = "none";

		var selectedValue = orderBookedSelect.value;

		if (selectedValue === "Yes") {
			// Show the OrderBookYes div
			orderBookYesDiv.style.display = "block";
		} else if (selectedValue === "No") {
			// Show the OrderBookNo div
			orderBookNoDiv.style.display = "block";
		}
	}
</script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
      // In your Javascript (external .js resource or <script> tag)
      $(document).ready(function() {
          $('.select2').select2();
      });
  </script>

  <script>
    $(document).ready(function() {
        $('#newspeciality').on('change', function() {
            if ($(this).val() === 'new') {
                $('#new_speciality_name').show();
            } else {
                $('#new_speciality_name').hide();
            }
        });
    });
</script>

<script>
    function openInIframe(url) {
        document.getElementById('inlineFrameExample').src = url;
    }
</script>

  @stack('scripts')

  @yield('extra-scripts')
  @yield('chart-scripts')

</body>

</html>
