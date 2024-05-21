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
        <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('salescalls.view-prescription-audits')}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-list-check"></i>
                            </span>
                <span class="hide-menu">View Prescription Audits</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('salescalls.view-orders-booked')}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-list-check"></i>
                            </span>
                <span class="hide-menu">View Orders Booked</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link" href="{{route('planner.calendar')}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar"></i>
                            </span>
                <span class="hide-menu">View Planner</span>
            </a>
        </li>
    @endrole
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">MESSAGES</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="/messages" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
            <span class="hide-menu">Inbox</span>
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
        <a class="sidebar-link" href="{{route('salescalls.create-cme')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-phone-plus"></i>
                    </span>
            <span class="hide-menu">Start RTD & CME Call</span>
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
        <a class="sidebar-link" href="{{route('salescalls.list-cme')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
            <span class="hide-menu">RTD & CMEs Attended</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('salescalls.view-prescription-audits')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
            <span class="hide-menu">View Prescription Audits</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('salescalls.view-orders-booked')}}" aria-expanded="false">
                    <span>
                        <i class="ti ti-list-check"></i>
                    </span>
            <span class="hide-menu">View Orders Booked</span>
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
            <span class="hide-menu">View Planner</span>
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
            <span class="hide-menu">Add Facility Appointment</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('planner.create-pharmacy-appointment')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
            <span class="hide-menu">Add Pharmacy Appointment</span>
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
        <span class="hide-menu">CUSTOMER MANAGEMENT</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('client-users.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
            <span class="hide-menu">Manage Doctors</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('facility-users.index', ['facility_type' => 'Clinic'])}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
            <span class="hide-menu">Manage Facilities</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('pharmacy-users.index', ['facility_type' => 'Pharmacy'])}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-plus"></i>
                        </span>
            <span class="hide-menu">Manage Pharmacies</span>
        </a>
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
        <a class="sidebar-link" href="{{route('salescalls.create-cme')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-phone-plus"></i>
                        </span>
            <span class="hide-menu">Start RTD & CME Call</span>
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
        <a class="sidebar-link" href="{{route('salescalls.list-cme')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-check"></i>
                        </span>
            <span class="hide-menu">RTD & CMEs Attended</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('salescalls.view-prescription-audits')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-check"></i>
                        </span>
            <span class="hide-menu">View Prescription Audits</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('salescalls.view-orders-booked')}}" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-check"></i>
                        </span>
            <span class="hide-menu">View Orders Booked</span>
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
            <span class="hide-menu">View Planner</span>
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
        <span class="hide-menu">CUSTOMER MANAGEMENT</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('client-users.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Doctors</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('facility-users.index', ['facility_type' => 'Clinic'])}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Facilities</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('pharmacy-users.index', ['facility_type' => 'Pharmacy'])}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Pharmacies</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('locations.location.index') }}" aria-expanded="false">
                                <span>
                                    <i class="ti ti-calendar-plus"></i>
                                </span>
            <span class="hide-menu">Manage Locations</span>
        </a>
    </li>
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
    @endrole

    @role('super_admin')
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">CUSTOMER MANAGEMENT</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('client-users.index') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Doctors</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('facility-users.index', ['facility_type' => 'Clinic'])}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Clinics</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('pharmacy-users.index', ['facility_type' => 'Pharmacy'])}}" aria-expanded="false">
                            <span>
                                <i class="ti ti-calendar-plus"></i>
                            </span>
            <span class="hide-menu">Manage Pharmacies</span>
        </a>
    </li>
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">MANUFACTURING</span>
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
        <a class="sidebar-link" href="{{route('input.index')}}" aria-expanded="false">
                        <span>
                          <i class="ti ti-login"></i>
                        </span>
            <span class="hide-menu">Manage Inputs</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('products.index')}}" aria-expanded="false">
                        <span>
                          <i class="ti ti-login"></i>
                        </span>
            <span class="hide-menu">Manage Products</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{route('input-batch.index')}}" aria-expanded="false">
                        <span>
                          <i class="ti ti-login"></i>
                        </span>
            <span class="hide-menu">Received Inputs</span>
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

    @role('store_manager')
    <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">MANUFACTURING</span>
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
        <a class="sidebar-link" href="{{route('input.index')}}" aria-expanded="false">
                        <span>
                          <i class="ti ti-login"></i>
                        </span>
            <span class="hide-menu">Inputs</span>
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
    @endrole
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
