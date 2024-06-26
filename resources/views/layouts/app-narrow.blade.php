<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@if(isset($pagetitle)){{$pagetitle}} @endif | {{config('app.name')}}</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/logos/favicon.png')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/styles.min.css')}}" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .fc-event:hover {
            color: #000 !important;
            background-color: #fff !important;
            text-decoration: none;
            font-weight:bold;

        }

        .readonly-label {
            border: none;
            background-color: transparent;
            outline: none;
            box-shadow: none;
        }
    </style>
</head>

<body>
  <!--  Body Wrapper -->
  
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item" style="display:none">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <a href="https://adminmart.com/product/modernize-free-bootstrap-admin-dashboard/" target="_blank" class="btn btn-primary" style="display:none">Download Free</a>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="{{asset('assets/images/profile/user-1.jpg')}}" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                      <a class="dropdown-item" href="{{ route('password.change') }}">
                          {{ __('Change Password') }}
                      </a>
                  </div>

				  <div class="message-body">
                      <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                          {{ __('Logout') }}
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </div>
                </div>

              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div id="main-body-content">
			@yield('content')
		</div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">For internal company use only. Do not share data or your credentials with anyone else</p>
        </div>
		<div class="px-6 text-center">
          <p class="mb-0 fs-1" style="font-size:9pt">Version 0.4.060923</p>
        </div>
      </div>
    </div>
  <script src="{{asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
  <script src="{{asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/sidebarmenu.js')}}"></script>
  <script src="{{asset('assets/js/app.min.js')}}"></script>
  <script src="{{asset('assets/libs/simplebar/dist/simplebar.js')}}"></script>
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
            //document.getElementById("class_div").style.display = "none";
        } else {
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

  @stack('scripts')

  @yield('extra-scripts')
  @yield('chart-scripts')

</body>

</html>
