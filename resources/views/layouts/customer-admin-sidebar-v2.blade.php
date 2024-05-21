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
                </div>
            </a>
        </li>
        <li>
            <a href="{{route('salescalls.view-orders-booked')}}"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/chat-icon-06.svg') }}" alt=""></span>
                <span> POBs</span> <div class="chat-user-count">
{{--                    <span class="nmbr">{{$ordersBookedCount}}</span>--}}
                </div>
            </a>
        </li>

        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/folder-icon-01.svg') }}" alt=""></span> <span> SAMPLES</span><div class="chat-user-count">
{{--                    <span class="nmbr">124</span>--}}
                </div>
            </a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('sample-batch.sample-inventory')}}">Samples Inventory</a></li>
                <li><a class="dropdown-item" href="{{route('sample-batch.report')}}">View Sample Report</a></li>
                <li><a class="dropdown-item" href="{{route('salescalls.view-sample-slips')}}">View Sample Slips </a></li>
            </ul>

        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-03.svg') }}" alt=""></span> <span> CUSTOMER MGMT </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{ route('client-users.index') }}">Manage Doctor</a></li>
                <li><a class="dropdown-item" href="{{ route('facility-users.index', ['facility_type' => 'Clinic']) }}">Manage Clinic</a></li>
                <li><a class="dropdown-item" href="{{ route('pharmacy-users.index', ['facility_type' => 'Pharmacy']) }}">Manage Pharmacies</a></li>
                <li><a class="dropdown-item" href="{{ route('locations.location.index') }}">Manage Locations</a></li>
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><span class="menu-side"><img src="{{ asset('assets-v2/img/icons/menu-icon-08.svg') }}" alt=""></span> <span> HR Section </span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                <li><a class="dropdown-item" href="{{route('leaves.user_index')}}">Leave Application</a></li>
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
