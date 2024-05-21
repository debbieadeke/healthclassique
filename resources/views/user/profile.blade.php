@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
<div class="container-fluid">
<div class="row">
    <div class="col-sm-7 col-6">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
            <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
            <li class="breadcrumb-item active">My Profile</li>
        </ul>
    </div>
    <div class="col-sm-5 col-6 text-end m-b-30">
        <a href="{{route('users.edit_profile')}}" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Edit Profile</a>
    </div>
</div>
<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-img-wrap">
                    <div class="profile-img">
                        <a href="#"><img class="avatar" src="{{ !empty($basic->image) ? asset($basic->image) : asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt=""></a>
                    </div>
                </div>
                <div class="profile-basic">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0">{{$user->first_name}} {{$user->last_name}}</h3>
                                <small class="text-muted">

                                    @if(auth()->check() && auth()->user()->roles->isNotEmpty())
                                        <?php
                                            $roleName = ucfirst(auth()->user()->roles->first()->name);
                                            // If the role is "user", rename it to "Sales Rep"
                                            if ($roleName === 'User') {
                                                $roleName = 'Sales Representative';
                                            }
                                        ?>
                                        {{ $roleName }}
                                    @endif
                                </small>
                                <div class="staff-id">Employee ID : {{ !empty($basic) && !is_null($basic->employee_id) ? $basic->employee_id : '' }}</div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Phone:</span>
                                    <span class="text"><a href="">{{ !empty($basic) && !is_null($basic->phone) ? $basic->phone : '' }}</a></span>
                                </li>
                                <li>
                                    <span class="title">Email:</span>
                                    <span class="text"><a href="">{{$user->email}}</a></span>
                                </li>
                                <li>
                                    <span class="title">Birthday:</span>
                                    <span class="text">{{ !empty($basic) && !is_null($basic->birthday) ? (new DateTime($basic->birthday))->format('jS F Y') : '' }}</span>
                                </li>
                                <li>
                                    <span class="title">Address:</span>
                                    <span class="text">{{ !empty($basic) && !is_null($basic->address) ? $basic->address : '' }}</span>
                                </li>
                                <li>
                                    <span class="title">Gender:</span>
                                    <span class="text">{{ !empty($basic) && !is_null($basic->gender) ? $basic->gender : '' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom" role="tablist">
        <li class="nav-item" role="presentation"><a class="nav-link active" href="#about-cont" data-bs-toggle="tab" aria-selected="true" role="tab">About</a></li>
        <li class="nav-item" role="presentation"><a class="nav-link" href="#bottom-tab2" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Profile</a></li>
        <li class="nav-item" role="presentation"><a class="nav-link" href="#bottom-tab3" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Messages</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active show" id="about-cont" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h3 class="card-title">Education Informations</h3>
                        <div class="experience-box">
                            <ul class="experience-list">
                                @foreach($educations as $key => $education)
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                        <div class="experience-content">
                                            <div class="timeline-content">
                                                <a href="#" class="name">{{ !empty($education) && !is_null($education->institution) ? $education->institution : '' }}</a>
                                                <div>{{ !empty($education) && !is_null($education->degree) ? $education->degree : '' }}</div>
                                                <span class="time">{{ !empty($education->starting_date) ? \Carbon\Carbon::parse($education->starting_date)->format('Y') : '' }} - {{ !empty($education->completion_date) ? \Carbon\Carbon::parse($education->completion_date)->format('Y') : '' }}</span>
                                            </div>
                                        </div>

                                </li>
                                @endforeach
                                @if($educations->isEmpty())
                                    <li>
                                        <div class="experience-content">
                                            <a href="#" class="name"> No Education Information</a>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="card-box ">
                        <h3 class="card-title">Experience</h3>
                        <div class="experience-box">
                            <ul class="experience-list">
                                @foreach($experiences as $key => $experience)
                                <li>
                                    <div class="experience-user">
                                        <div class="before-circle"></div>
                                    </div>
                                    <div class="experience-content">
                                        <div class="timeline-content">
                                            <a href="#/" class="name">{{ !empty($experience) && !is_null($experience->company_name) ? $experience->company_name : '' }}</a>
                                            @if (!empty($experience->period_from) && !empty($experience->period_to))
                                                <?php
                                                    $from = \Carbon\Carbon::parse($experience->period_from);
                                                    $to = \Carbon\Carbon::parse($experience->period_to);
                                                    $diff = $from->diff($to);
                                                    $formattedDiff = '';

                                                    if ($diff->y > 0) {
                                                        $formattedDiff .= $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ';
                                                    }

                                                    if ($diff->m > 0) {
                                                        $formattedDiff .= $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ';
                                                    }

                                                    if ($diff->d > 0) {
                                                        $formattedDiff .= $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ';
                                                    }
                                                ?>

                                            @endif
                                            <span class="time">{{ $from->format('M Y') }} - {{ $to->format('M Y') }}  ({{ $formattedDiff }})</span>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                @if($educations->isEmpty())
                                    <li>
                                        <div class="experience-content">
                                            <a href="#" class="name"> No Experience Information</a>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="bottom-tab2" role="tabpanel">
            Tab content 2
        </div>
        <div class="tab-pane" id="bottom-tab3" role="tabpanel">
            Tab content 3
        </div>
    </div>
</div>
</div>
<div class="notification-box">
<div class="msg-sidebar notifications msg-noti">
    <div class="topnav-dropdown-header">
        <span>Messages</span>
    </div>
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 1242px;"><div class="drop-scroll msg-list-scroll" id="msg_list" style="overflow: hidden; width: auto; height: 1242px;">
            <ul class="list-box">
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">R</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Richard Miles </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item new-message">
                            <div class="list-left">
                                <span class="avatar">J</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">John Doe</span>
                                <span class="message-time">1 Aug</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">T</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Tarah Shropshire </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">M</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Mike Litorus</span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">C</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Catherine Manseau </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">D</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Domenic Houston </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">B</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Buster Wigton </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">R</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Rolland Webber </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">C</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author"> Claire Mapes </span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">M</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Melita Faucher</span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">J</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Jeffery Lalor</span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">L</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Loren Gatlin</span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="chat.html">
                        <div class="list-item">
                            <div class="list-left">
                                <span class="avatar">T</span>
                            </div>
                            <div class="list-body">
                                <span class="message-author">Tarah Shropshire</span>
                                <span class="message-time">12:28 AM</span>
                                <div class="clearfix"></div>
                                <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
        </div><div class="slimScrollBar" style="background: rgb(135, 135, 135); width: 4px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 0px; z-index: 99; right: 1px; height: 776px;"></div><div class="slimScrollRail" style="width: 4px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
    <div class="topnav-dropdown-footer">
        <a href="chat.html">See all messages</a>
    </div>
</div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
@stop
