@extends('layouts.app-v2')

@section('content-v2')
    @include('messenger.partials.flash')

    <div class="container">
        <div class="row">
            <div class="col-9"></div>
            <div class="col-3"><a href="/messages/create" aria-expanded="false" style="font-size: 17px; font-weight: bold">
                    Compose New Message
                </a></div>
        </div>
    </div>
    {{-- @each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads') --}}

    <!-- /Page Header -->
    <div class="row" style="height: 100%">
        <div class="col-xl-4 d-flex" style="height: 100%">
            <div class="card chat-box-clinic ">
                <div class="chat-widgets">
                    <div class="top-liv-search top-chat-search" style="display: none">
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
                    @each('messenger.partials.thread-v2', $threads, 'thread', 'messenger.partials.no-threads')



                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- Chat -->
            <div class="card chat-message-box" style="height: 100%; display: flex; flex-direction: column;">
                <div class="card-body p-0" style="flex-grow: 1; height: 550px">
                    <div class="chat-body">
                        <ul class="list-unstyled chat-message" style="display: none">
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

                        <iframe id="inlineFrameExample" title="Inline Frame Example"></iframe>

                    </div>

                </div>
            </div>
            <!-- /Chat -->
        </div>
    </div>
@stop
