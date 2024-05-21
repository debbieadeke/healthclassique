@extends('layouts.app-v2')

@section('content-v2')
    @include('messenger.partials.flash')

    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="chat.html">App </a></li>
                        <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                        <li class="breadcrumb-item active">Chat</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-xl-4 d-flex">
                <div class="card chat-box-clinic ">
                    <div class="chat-widgets">
                        <div class="top-liv-search top-chat-search">
                            <form>
                                <div class="chat-search">
                                    <div class="input-block me-2 mb-0">
                                        <input type="text" class="form-control" placeholder="Search chat">
                                        <a class="btn" ><img src="assets/img/icons/search-normal.svg" alt=""></a>
                                    </div>
                                    <div class="add-search">
                                        <a href="javascript:;"><i class="feather-plus"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-05.jpg" alt="img"></a>
                                <span class="active-users bg-info"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> William Sami	</h5>
                                    <div class="chat-user-time">
                                        <p>10:22 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                    <div class="chat-user-count">
                                        <span>3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-02.jpg" alt="img"></a>
                                <span class="active-users"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> Mark Haloi	</h5>
                                    <div class="chat-user-time">
                                        <p>2hrs ago</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-03.jpg" alt="img"></a>
                                <span class="active-users"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> William Kiptoo	</h5>
                                    <div class="chat-user-time">
                                        <p>11:35 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                    <div class="chat-user-count">
                                        <span>4</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-04.jpg" alt="img"></a>
                                <span class="active-users bg-info"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> Bernardo James	</h5>
                                    <div class="chat-user-time">
                                        <p>11:35 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-06.jpg" alt="img"></a>
                                <span class="active-users bg-info"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> Alexandr Donnelly	</h5>
                                    <div class="chat-user-time">
                                        <p>11:35 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                    <div class="chat-user-count">
                                        <span>3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-07.jpg" alt="img"></a>
                                <span class="active-users"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> Regina Kioo	</h5>
                                    <div class="chat-user-time">
                                        <p>11:35 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                </div>
                            </div>
                        </div>
                        <div class="chat-user-group mb-0 d-flex align-items-center">
                            <div class="img-users call-user">
                                <a href="profile.html"><img src="assets/img/profiles/avatar-08.jpg" alt="img"></a>
                                <span class="active-users bg-info"></span>
                            </div>
                            <div class="chat-users">
                                <div class="user-titles d-flex">
                                    <h5> Forest Kroch</h5>
                                    <div class="chat-user-time">
                                        <p>11:35 AM</p>
                                    </div>
                                </div>
                                <div class="user-text d-flex">
                                    <p>Millfield Runda School Expands its...</p>
                                    <div class="chat-user-count">
                                        <span>3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <!-- Chat -->
                <div class="card chat-message-box">
                    <div class="card-body p-0">
                        <div class="chat-body">
                            <ul class="list-unstyled chat-message">
                                <li class="media d-flex received">
                                    <div class="avatar flex-shrink-0">
                                        <img src="assets/img/profiles/avatar-05.jpg" alt="User Image" class="avatar-img rounded-circle">
                                    </div>
                                    <div class="media-body flex-grow-1">
                                        <div class="msg-box">
                                            <div class="message-sub-box">
                                                <h4>Auma Lalema</h4>
                                                <p>In an exciting development for education in Nairobi, Millfield Runda School has officially unveiled its brand-new</p>
                                                <span>06:00 PM, 30 Sep 2022</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="media d-flex sent">
                                    <div class="media-body flex-grow-1">
                                        <div class="msg-box">
                                            <div class="message-sub-box">
                                                <p>How likely are you to recommend our company to your friends and family ?</p>
                                                <span>06:00 PM, 30 Sep 2022</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="media d-flex received">
                                    <div class="avatar flex-shrink-0">
                                        <img src="assets/img/profiles/avatar-05.jpg" alt="User Image" class="avatar-img rounded-circle">
                                    </div>
                                    <div class="media-body flex-grow-1">
                                        <div class="msg-box">
                                            <div class="message-sub-box">
                                                <h4>Auma Lalema</h4>
                                                <p>non tellus dignissim </p>
                                                <span>06:32 PM Yesterday</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="media d-flex sent">
                                    <div class="media-body flex-grow-1">
                                        <div class="msg-box">
                                            <div class="message-sub-box">
                                                <p>Millfield Runda School Expands its Horizons -of-the-Art Campus in Nairobi
                                                </p>
                                                <p class="mb-0">Vivamus sed dictum</p>
                                                <span>06:50PM Today</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="media d-flex received">
                                    <div class="avatar flex-shrink-0">
                                        <img src="assets/img/profiles/avatar-05.jpg" alt="User Image" class="avatar-img rounded-circle">
                                    </div>
                                    <div class="media-body flex-grow-1">
                                        <div class="msg-box">
                                            <div class="message-sub-box">
                                                <h4>Auma Lalema</h4>
                                                <p>aliquam ut a ex</p>
                                                <span>5min Ago</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="chat-footer-box">
                            <div class="discussion-sent">
                                <div class="row gx-2">
                                    <div class="col-lg-12 ">
                                        <div class="footer-discussion">
                                            <div class="inputgroups">
                                                <input type="text" placeholder="Type your Message here...">

                                                <div class="send-chat position-icon comman-flex">
                                                    <a href="javascript:;" class="text-white">
                                                        Send															</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Chat -->
            </div>
        </div>
    </div>
@stop
