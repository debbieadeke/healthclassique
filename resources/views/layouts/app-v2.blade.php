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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets-v2/css/feather.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/style.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-v2/css/custom.css') }}">

    <style>

        html, body {
            height: 100%;
            margin: 0;
        }

    </style>
</head>


<body>
<div class="main-wrapper">
    <div class="header">
        <div class="header-left">
            <a href="{{route('home')}}" class="logo">
                <img src="{{ asset('assets-v2/img/logo.png') }}" height="44" alt="">
            </a>
        </div>
        <a id="toggle_btn" class="pt-4" href="javascript:void(0);"><img src="{{ asset('assets-v2/img/icons/bar-icon.svg') }}" alt=""></a>
        <a id="mobile_btn" class="mobile_btn float-start " href="#sidebar"><img src="{{ asset('assets-v2/img/icons/bar-icon.svg') }}" alt=""></a>
        <div class="top-nav-search mob-view">
        </div>
        <ul class="nav user-menu float-end">
            <li class="nav-item dropdown d-none d-md-block pt-2">
                <a href="#" class="dropdown-toggle nav-link notification" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets-v2/img/icons/note-icon-01.svg') }}" alt="">
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <span>Notifications</span>
                    </div>
                    <div class="drop-scroll">
                        <ul class="notification-list">
                            <!-- Notification items go here, update the links accordingly -->
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer">
                        <a href="{{ url('activities.html') }}">View all Notifications</a>
                    </div>
                </div>
            </li>
            <li style="visibility:hidden" class="nav-item dropdown d-none d-md-block">
                <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link">
                    <img src="{{ asset('assets-v2/img/icons/note-icon-01.svg') }}" alt=""><span class="pulse"></span>
                </a>
            </li>
            <li class="nav-item dropdown has-arrow user-profile-list">
                <a href="#" class="dropdown-toggle nav-link user-link" data-bs-toggle="dropdown">
                    <div class="user-names">
                        <h5>
                            @if(Auth::check())
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            @else
                                Guest
                            @endif
                        </h5>
                        <span>{{ ucfirst(auth()->user()->roles->first()->name) }}</span>
                    </div>
                    <span class="user-img">
				   <img src="{{ isset($profileImage) ? asset($profileImage) : asset('assets-v2/img/user-06.jpg') }}" alt="User Profile Image">
				</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('users.myProfile') }}">My Profile</a>
                    <a class="dropdown-item" href="{{ route('users.edit_profile') }}">Edit Profile</a>
                    <a class="dropdown-item" href="{{ route('password.change') }}">Change Password</a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form-v2').submit();">Logout</a>

                    <form id="logout-form-v2" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>

        </ul>
        <div class="dropdown mobile-user-menu float-end">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('users.myProfile') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('password.change') }}">Change Password</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form-v2').submit();">Logout</a>

                <form id="logout-form-v2" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            @role('super_admin')
                @include('layouts.admin-sidebar-v2')
            @endrole

            @role('manager')
                @include('layouts.manager-sidebar-v2')
            @endrole

            @role('user')
                @include('layouts.sidebar-v2')
            @endrole

            @role('customer_admin')
                @include('layouts.customer-admin-sidebar-v2')
            @endrole

            @role('store_manager')
                @include('layouts.store-manager-sidebar-v2')
            @endrole

            @role('office')
            @include('layouts.office-sidebar')
            @endrole
        </div>

    </div>
    <div class="page-wrapper">
        <div class="content">
            @yield('content-v2')
        </div>
    </div>
    <div class="footer-spacer"></div>
   <div>
{{--       <footer class="content-footer footer bg-footer-theme text-bg-light">--}}
{{--           <div class="container-xxl">--}}
{{--               <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">--}}
{{--                   <div>--}}
{{--                       © <script>--}}
{{--                           document.write(new Date().getFullYear())--}}

{{--                       </script>&nbsp;Copyright <a href="https://http://greyglass.co.ke/" target="_blank" class="footer-link text-primary fw-medium">GreyGlass Technologies</a> All Rights Reserved--}}
{{--                   </div>--}}
{{--                   <div class="d-none d-lg-inline-block">--}}

{{--                       <a href="{{route('users.user_privacy_policy')}}" class="footer-link me-4" >Privacy Policy</a>--}}
{{--                       <a href="#" class="footer-link me-4" target="_blank">License</a>--}}
{{--                       <a href="#" target="_blank" class="footer-link me-4">Documentation</a>--}}
{{--                       --}}{{--                    <a href="https://pixinvent.ticksy.com/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>--}}

{{--                   </div>--}}
{{--               </div>--}}
{{--           </div>--}}
{{--       </footer>--}}
   </div>
</div>

<div class="sidebar-overlay" data-reff=""></div>
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    .main-wrapper {
        min-height: 100%;
        position: relative;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
    }

    .footer-spacer {
        height: 30px; /* Adjust this value based on your footer's height */
    }
</style>
<script>
    $(document).ready(function() {
        // Replace '123' with the actual user ID or fetch it from your session
        var userId = {{ ucfirst(auth()->user()->id) }};
        console.log(userId);

        // Make an AJAX request to fetch the user's basic information
        $.ajax({
            url: '/api/user/basic-info/' + userId,
            method: 'GET',
            success: function(response) {
                // Assuming response contains user basic info including image path
                var imagePath = response.image;
                $('#user-image').attr('src', imagePath);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
</script>
<script>
    function updateNotifications() {
        $.get("{{ route('notification.notifications') }}", function(data) {
            // Clear existing notifications
            $(".notification-list").empty();

            if (data.length > 0) {
                // Show the pulse if there are notifications
                $(".dropdown-toggle.nav-link.notification").append("<span class='pulse'></span>");

                // Append new notifications
                data.forEach(function(notification) {
                    // Parse the JSON data to extract the message
                    var jsonData = JSON.parse(notification.data);
                    var message = jsonData.message;
                    var route = notification.route;

                    // Extract a short indication of the message (e.g., first 50 characters)
                    var shortMessage = message.substring(0, 50) + (message.length > 50 ? '...' : '');

                    // Create a clickable notification item
                    var listItem = $("<li><a href='" + route + "'>" + shortMessage + "</a></li>");

                    // Append the item to the notification list
                    $(".notification-list").append(listItem);
                });
            } else {
                // Hide the pulse if there are no notifications
                $(".pulse").hide();

                // Display "No notifications" in the dropdown
                $(".notification-list").append("<li>No notifications</li>");
            }


        });
    }

    // Update notifications every 30 seconds (adjust as needed)
    setInterval(updateNotifications, 30000);
</script>

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
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets-v2/plugins/datatables/datatables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

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
    $(document).ready(function() {
        let table = new DataTable('#myDataTable', {
            "searching": true,
            "lengthChange": true
        });

        new DataTable('#example', {
            "searching": true,
            "lengthChange": true
        });
    });
</script>

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
