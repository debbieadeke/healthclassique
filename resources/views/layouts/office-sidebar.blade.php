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
