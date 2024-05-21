<div id="sidebar-menu" class="sidebar-menu">
    <ul>
        <li class="menu-title">Main</li>
        <li>
            <a href="{{route('home')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-01.svg') }}" alt=""></span> <span> Dashboard </span></a>
        </li>
        <li>
            <a href="/messages"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/draft.svg') }}" alt=""></span> <span> Messages </span>
                <div class="chat-user-count">
                    @if ($unReadMessagesCount > 0)
                        <span class="nmbr">{{ $unReadMessagesCount }}</span>
                    @endif
                </div></a>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-08.svg') }}" alt=""></span> <span> HR Management </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('leaves.admin_index')}}">Leaves</a></li>
                <li><a class="dropdown-item" href="{{route('leaves.manager_index')}}">manager Leaves</a></li>
                <li><a class="dropdown-item" href="{{route('leaves.users_leave_days')}}">Leaves Days</a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-09.svg') }}" alt=""></span> <span> Sales Analysis </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('sale.index')}}">File Upload</a></li>
                <li><a class="dropdown-item" href="{{route('sale.monthlyReport_index')}}">Monthly Report</a></li>
{{--                <li><a class="dropdown-item" href="{{route('sale.fullreport_index')}}">Full Report</a></li>--}}
                <li><a class="dropdown-item" href="{{route('sale.quarter-report')}}">Quarterly Report</a></li>
                <li><a class="dropdown-item" href="{{route('sale.delete_sales')}}">Delete Sales</a></li>

            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/document-icon.svg') }}" alt=""></span> <span> REPORTS </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('home')}}">Employee Performance</a></li>
                <li><a class="dropdown-item" href="{{route('planner.calendar_version2')}}">View Planner</a></li>
            </ul>
        </li>
        <li>
            <a href="{{route('salescalls.view-prescription-audits')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-15.svg') }}" alt=""></span>PxN Audits
                <div class="chat-user-count">
{{--                    <span class="nmbr">{{$pxnAuditsCount}}</span>--}}
                </div>
            </a>
        </li>
        <li>
            <a href="{{route('salescalls.view-orders-booked')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/chat-icon-06.svg') }}" alt=""></span>POBs <div class="chat-user-count">
{{--                    <span class="nmbr">{{$ordersBookedCount}}</span>--}}
                </div>
            </a>
        </li>
        <li>
            <a href="{{route('salescalls.view-general-uploads')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/printer.svg') }}" alt=""></span>General Uploads <div class="chat-user-count">
                </div>
            </a>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/call-incoming.svg') }}" alt=""></span> <span>Sales Calls</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('salescalls.admin_new_doctor')}}"> Approve New Doctors</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.admin_new_pharmacy')}}">Approve New Facilities</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.approve-reps-sale')}}">Approve Sales</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.titles')}}">Doctors Titles</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.speciality')}}">Doctor Speciality</a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/folder-icon-01.svg') }}" alt=""></span> <span> SAMPLES </span><span class="menu-arrow"></span><div class="chat-user-count">
                </div>
            </a>
            <ul style="display: none;">
{{--                <li><a class="dropdown-item" href="{{route('sample-batch.users')}}">Sample Users</a></li>--}}
{{--                <li><a class="dropdown-item" href="{{route('sample-batch.approve')}}">Approve Requests</a></li>--}}
{{--                <li><a class="dropdown-item" href="{{route('sample-batch.issued')}}">Issue Sample</a></li>--}}
                <li><a class="dropdown-item" href="{{route('sample-batch.approve-sample-request')}}">Approve Requests</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.issue-sample-request')}}">Issue Sample</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.report')}}">View Sample Report</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.sample-balance')}}">Sample Balance </a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.sample-inventory')}}">Samples Inventory</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.edit-sample-request')}}">Edit Sample Request</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.user_sample_report')}}">User Sample Report</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.view-sample-slips')}}">View Sample Slips </a></li>

            </ul>

        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/calendar.svg') }}" alt=""></span> <span> PLANNER </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
{{--                <li><a class="dropdown-item" href="{{route('planner.calendar')}}">View Planner</a></li>--}}
                <li><a class="dropdown-item" href="{{route('planner.calendar_version2')}}">View Planner</a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-07.svg') }}" alt=""></span> <span> Incentives </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{ route('incentive.tier-products') }}">Tier Products</a></li>
                <li><a class="dropdown-item" href="{{ route('incentive.incentive-metrics') }}">Incentives Metrics</a></li>
                <li><a class="dropdown-item" href="{{ route('incentive.users-incentive') }}">Reps Incentives</a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-03.svg') }}" alt=""></span> <span> CUSTOMER MGMT </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{ route('clients.index_two') }}">Manage Doctor</a></li>
                <li><a class="dropdown-item" href="{{ route('managefacilities.admin_clinic') }}">Manage Clinic</a></li>
                <li><a class="dropdown-item" href="{{ route('managepharmacies.admin_pharmacy') }}">Manage Pharmacies</a></li>
                <li><a class="dropdown-item" href="{{ route('locations.location.index') }}">Manage Locations</a></li>
                <li><a class="dropdown-item" href="{{ route('targets.admin-index') }}">Manage Targets</a></li>
                <li><a class="dropdown-item" href="{{ route('targets.accumulated_targets') }}">View Targets</a></li>
            </ul>
        </li>

        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/chat-icon-03.svg') }}" alt=""></span> <span> MANUFACTURING </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('suppliers.index')}}">Manage Suppliers</a></li>
                <li><a class="dropdown-item" href="{{route('input.index')}}">Manage Inputs</a></li>
                <li><a class="dropdown-item" href="{{route('products.index')}}">Manage Products</a></li>
                <li><a class="dropdown-item" href="{{route('input-batch.index')}}">Received Inputs</a></li>
                <li><a class="dropdown-item" href="{{ route('report.stock') }}">Stock Report</a></li>
                <li><a class="dropdown-item" href="{{ route('production-order.index') }}">Production Orders</a></li>
                <li><a class="dropdown-item" href="{{route('manufacturing.chemstore_form')}}">Chemstore Form</a></li>
                <li><a class="dropdown-item" href="{{route('manufacturing.production_form')}}">Production Form</a></li>
                <li><a class="dropdown-item" href="{{ route('manufacturing.chem_store') }}">Chemstore Report</a></li>
                <li><a class="dropdown-item" href="{{ route('manufacturing.production') }}">Production Report</a></li>
            </ul>
        </li>

        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/profile-user.svg') }}" alt=""></span> <span> Users Mgmt </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('users.index')}}">Users</a></li>
                <li><a class="dropdown-item" href="{{route('users.privacy_policy')}}">Privacy Policy</a></li>
            </ul>
        </li>
    </ul>
    <div class="logout-btn">
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/logout.svg') }}" alt=""></span> <span>Logout</span></a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
