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
    <script src="https://kit.fontawesome.com/9800030203.js" crossorigin="anonymous"></script>
</head>

<body>
  <!--  Body Wrapper -->
  @if(isset($role))
      @if ($role == "super_admin")
          <div class="page-wrapper mini-sidebar" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="mini-sidebar" data-header-position="fixed">
      @else
          <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
               data-sidebar-position="fixed" data-header-position="fixed">
      @endif
  @else
      @role('super_admin')
      <div class="page-wrapper mini-sidebar" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="mini-sidebar" data-sidebar-position="fixed" data-header-position="fixed">
      @endrole
  @endif
  @role('manager')
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  @endrole
  @role('user')
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  @endrole
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="#" class="text-nowrap logo-img">
            <img src="{{asset('assets/images/logos/logo.jpg')}}" width="130" alt="" style="padding-left:50px"  />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{route('home')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
              @role('user')
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">SALES CALLS</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.create')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
                    <span class="hide-menu">Start Clinic Call</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.create-doctor')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
                    <span class="hide-menu">Start Doctor Call</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.create-pharmacy')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
                    <span class="hide-menu">Start Pharmacy Call</span>
                    </a>
                </li>
				<li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.create-roundtable')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
                    <span class="hide-menu">Start RoundTable Call</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.create-cme')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
                    <span class="hide-menu">Start CME Call</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.list')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
                    <span class="hide-menu">Clinic Sales Calls</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.list-doctor')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
                    <span class="hide-menu">Doctor Sales Calls</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.list-pharmacy')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
                    <span class="hide-menu">Pharmacy Sales Calls</span>
                    </a>
                </li>
				<li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.list-roundtable')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
                    <span class="hide-menu">RoundTable Sales Calls</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('salescalls.list-cme')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
                    <span class="hide-menu">CMEs Attended</span>
                    </a>
                </li>
				<li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PLANNER</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('planner.calendar')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-calendar"></i>
                    </span>
                    <span class="hide-menu">View Calendar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('planner.create-appointment')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-calendar-plus"></i>
                    </span>
                    <span class="hide-menu">Add Doctor Appointment</span>
                    </a>
                </li>
                <li class="sidebar-item">
                      <a class="sidebar-link" href="{{route('planner.create-facility-appointment')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
                          <span class="hide-menu">Add Facilities Appointment</span>
                      </a>
                  </li>
                <li class="sidebar-item">
                      <a class="sidebar-link" href="{{route('planner.list-appointments')}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
                          <span class="hide-menu">Reschedule Appointments</span>
                      </a>
                  </li>
                <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                      <span class="hide-menu">SETTINGS</span>
                  </li>
                <li class="sidebar-item">
                      <a class="sidebar-link" href="{{ route('locations.location.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
                          <span class="hide-menu">Manage Locations</span>
                      </a>
                  </li>
              @endrole
              @role('manager')
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">USERS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-buttons.html" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Add New</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-alerts.html" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-circle"></i>
                </span>
                <span class="hide-menu">Manage</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">REPORTS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-login.html" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                <span class="hide-menu">Login</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-register.html" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Register</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">SYSTEM SETUP</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./icon-tabler.html" aria-expanded="false">
                <span>
                  <i class="ti ti-mood-happy"></i>
                </span>
                <span class="hide-menu">Icons</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Sample Page</span>
              </a>
            </li>
              @endrole

			  @role('super_admin')
                <li class="nav-small-cap">
                  <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                  <span class="hide-menu">REPORTS</span>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="{{route('home')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-login"></i>
                    </span>
                    <span class="hide-menu">Employee Perfomance</span>
                  </a>
                </li>
                <li class="nav-small-cap">
                  <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                  <span class="hide-menu">MANUFACTURING</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{route('input.index')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-login"></i>
                    </span>
                        <span class="hide-menu">Inputs</span>
                    </a>
                </li>

              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{route('suppliers.index')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-login"></i>
                    </span>
                      <span class="hide-menu">Manage Suppliers</span>
                  </a>
              </li>



              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{route('input-batch.index')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-login"></i>
                    </span>
                      <span class="hide-menu">Receive Inputs</span>
                  </a>
              </li>

              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{route('report.stock')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-report"></i>
                    </span>
                      <span class="hide-menu">Stock Report</span>
                  </a>
              </li>


              <li class="sidebar-item">
                  <a class="sidebar-link" href="{{route('production-order.index')}}" aria-expanded="false">
                    <span>
                      <i class="ti ti-login"></i>
                    </span>
                      <span class="hide-menu">Production Orders</span>
                  </a>
              </li>

              @endrole
              <li class="nav-small-cap">
                  <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                  <span class="hide-menu">OTHER</span>
              </li>
          </ul>
		  <div class="hide-menu position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">&nbsp;
              </div>
            </div>
          </div>
          <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded" style="display:none">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
                <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
              </div>
              <div class="unlimited-access-img">
                <img src="{{asset('assets/images/backgrounds/rocket.png')}}" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
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
