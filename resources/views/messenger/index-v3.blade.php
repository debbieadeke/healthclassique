@extends('layouts.app-v2')

@section('content-v2')

<style>
    .chat-widgets{
        height: 745px;
        overflow-y: auto;
    }
    .onDeleteThread:hover{
        color: red;
        cursor: pointer;
    }
</style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard </a></li>
                    <li class="breadcrumb-item"><i class="feather-chevron-right"></i></li>
                    <li class="breadcrumb-item active">Chat</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    <div class="row">
        <div class="col-xl-4 d-flex" style="height: 675px; overflow-y: auto;">
            <div class="card chat-box-clinic ">
                <div class="chat-widgets">
                    <div class="top-liv-search top-chat-search">
                        <form>
                            <div class="chat-search">
                                <div class="input-block me-2 mb-0">
                                    <input type="text" class="form-control" placeholder="Search chat">
                                    <a class="btn" ><img src="{{asset('assets-v2/img/icons/search-normal.svg')}}" alt=""></a>
                                </div>
                                <div class="add-search">
                                    <a href="{{ route('messages.create') }}"><i class="feather-plus"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @foreach ($threads as $key => $thread)
                                <?php $class_name = 'v-pills-home-'.$key.'-'.$thread->id; ?>

                                <div class="chat-user-group d-flex align-items-center" onclick="setThreadId('{{$thread->id}}'); markMessagesAsRead('{{ $thread->id }}')">
                                    @if ($thread->participants->count() > 0)
                                        @if ($thread->participants->count() > 2)
                                            <div class="img-users call-user">
                                                <a href="#"><img src="{{ asset('assets-v2/img/profiles/avatar-03.jpg')}}" alt="img"></a>
                                                <span class="active-users bg-info"></span>
                                            </div>
                                            @if ($key == 0)
                                                <a class="active w-100" href="#" id="{{$class_name}}-tab" data-bs-toggle="pill" data-bs-target="#{{$class_name}}" role="tab" aria-controls="{{$class_name}}" aria-selected="true">
                                                    @else
                                                <a class="w-100" href="#" id="{{$class_name}}-tab" data-bs-toggle="pill" data-bs-target="#{{$class_name}}" role="tab" aria-controls="{{$class_name}}" aria-selected="false">
                                            @endif
                                            <div class="chat-users">
                                                <div class="user-titles d-flex">
                                                    <h5>Group Message</h5>
                                                    <div class="chat-user-time">
                                                            <?php
                                                            // Get the creation date of the thread
                                                            $createdAt = \Carbon\Carbon::parse($thread->created_at);
                                                            // Get the current date and time
                                                            $now = \Carbon\Carbon::now();
                                                            // Calculate the difference in minutes between the creation date and now
                                                            $minutesDiff = $now->diffInMinutes($createdAt);
                                                            ?>

                                                        @if ($createdAt->isToday())
                                                            @if ($minutesDiff < 60)
                                                                <p>{{ $minutesDiff }} min ago</p>
                                                            @elseif ($minutesDiff >= 60 && $minutesDiff < 120)
                                                                <p>{{ $createdAt->diffInHours($now) }} hour ago</p>
                                                            @else
                                                                <p>{{ $createdAt->diffForHumans() }}</p>
                                                            @endif
                                                        @else
                                                            <p>{{ $createdAt->diffForHumans() }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="user-text d-flex">
                                                    <p>{{ $thread->subject }}</p>
                                                    <div class="chat-user-count" style="display: block" id="unread-message-count-{{ $thread->id }}">
                                                        @if ($thread->userUnreadMessagesCount(Auth::id()) > 0)
                                                            <div class="chat-user-count" style="display: block">
                                                                <span>{{ $thread->userUnreadMessagesCount(Auth::id()) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <?php
                                                $receiver = $thread->participants->where('user_id', '!=', auth()->id())->first();
                                            ?>
                                            <div class="img-users call-user">
                                                <a href="#"><img src="{{ !empty($receiver->user->userBasicInfo->image) ? asset($receiver->user->userBasicInfo->image) : asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt="img"></a>
                                                <span class="active-users bg-info"></span>
                                            </div>
                                            @if ($key == 0)
                                                <a class="active w-100" href="#" id="{{$class_name}}-tab" data-bs-toggle="pill" data-bs-target="#{{$class_name}}" role="tab" aria-controls="{{$class_name}}" aria-selected="true">
                                            @else
                                                <a class="w-100" href="#" id="{{$class_name}}-tab" data-bs-toggle="pill" data-bs-target="#{{$class_name}}" role="tab" aria-controls="{{$class_name}}" aria-selected="false">
                                            @endif
                                            <div class="chat-users">
                                                <div class="user-titles d-flex">
                                                    <h5>{{ $receiver->user->first_name }} {{ $receiver->user->last_name }}</h5>
                                                    <div class="chat-user-time">
                                                            <?php
                                                            // Get the creation date of the thread
                                                            $createdAt = \Carbon\Carbon::parse($thread->updated_at);
                                                            // Get the current date and time
                                                            $now = \Carbon\Carbon::now();
                                                            // Calculate the difference in minutes between the creation date and now
                                                            $minutesDiff = $now->diffInMinutes($createdAt);
                                                            ?>

                                                        @if ($createdAt->isToday())
                                                            @if ($minutesDiff < 60)
                                                                <p>{{ $minutesDiff }} min ago</p>
                                                            @elseif ($minutesDiff >= 60 && $minutesDiff < 120)
                                                                <p>{{ $createdAt->diffInHours($now) }} hour ago</p>
                                                            @else
                                                                <p>{{ $createdAt->diffForHumans() }}</p>
                                                            @endif
                                                        @else
                                                            <p>{{ $createdAt->diffForHumans() }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="user-text d-flex">
                                                    <p>{{ $thread->subject }}</p>
                                                    <div class="chat-user-count" style="display: block" id="unread-message-count-{{ $thread->id }}">
                                                        @if ($thread->userUnreadMessagesCount(Auth::id()) > 0)
                                                            <div class="chat-user-count" style="display: block">
                                                                <span>{{ $thread->userUnreadMessagesCount(Auth::id()) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        @endif
                                    @endif
                                    </a>
                                    <!-- Move the delete button outside of the anchor tag -->
{{--                                    <form action="{{ route('messages.delete',$thread->id)}}" method="POST">--}}
{{--                                        @method('DELETE')--}}
{{--                                        @csrf--}}

{{--                                        <input type="hidden" name="data" value="thread">--}}
{{--                                        <button type="submit" class="btn btn-link onDeleteThread">Delete</button>--}}
{{--                                    </form>--}}
                                </div>
                            @endforeach
                        </div>

                    </div>


                </div>
            </div>
        </div>

        <div class="col-xl-8">

            @foreach ($threads as $key1 => $thread)
                    <?php
                    $receiver = $thread->participants->where('user_id', '!=', auth()->id())->first();
                    ?>

                <div class="card chat-box" data-thread-id="{{$thread->id}}"  style="display: {{$thread->id == $currentThreadId->id ? 'block' : 'none'}}">
                    <div class="chat-search-group">
                        @if ($thread->participants->count() > 2)
                            <div class="chat-user-group mb-0 d-flex align-items-center">
                                <div class="img-users call-user">
                                    <a href="#"><img src="{{ asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt="img"> </a>
                                    <span class="active-users bg-info"></span>
                                </div>
                                <div class="chat-users">
                                    <div class="user-titles">
                                        <h5>Group Message</h5>
                                    </div>
                                    <div class="user-text">
                                        <p>Announcement ({{$thread->participants->count()}} Members)</p> <!-- Replace with appropriate message content -->
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="chat-user-group mb-0 d-flex align-items-center">
                                <div class="img-users call-user">
                                    <a href="#"><img src="{{ !empty($receiver->user->userBasicInfo->image) ? asset($receiver->user->userBasicInfo->image) : asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt="img"> </a>
                                    <span class="active-users bg-info"></span>
                                </div>
                                <div class="chat-users">
                                    <div class="user-titles">
                                        <h5>{{ $receiver->user->first_name }} {{ $receiver->user->last_name }}</h5>
                                    </div>
                                    <div class="user-text">
                                        <p>Last Seen : 8:00am</p> <!-- Replace with appropriate message content -->
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="chat-search-list">
                            <ul>
                                <li><a href="#"><img src="{{asset('assets-v2/img/icons/chat-icon-01.svg')}}" alt="img"></a></li>
                                <li><a href="#"><img src="{{asset('assets-v2/img/icons/chat-icon-02.svg')}}" alt="img"></a></li>
                                <li><a href="#"><img src="{{asset('assets-v2/img/icons/chat-icon-03.svg')}}" alt="img"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="card chat-message-box" >
                <div class="card-body p-0">
                    <div class="chat-body p-2" style="height: 400px; overflow-y: auto;">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach ($threads as $key1 => $thread)
                                    <?php $class_name = 'v-pills-home-'.$key1.'-'.$thread->id; ?>

                                    <!-- Loop through here using the id above to get all the  messages for  single thread-->
                                    <ul class="list-unstyled chat-message collapse collapse-horizontal tab-pane fade @if($key1 == 0) show active @endif" id="{{$class_name}}" role="tabpanel" aria-labelledby="{{$class_name}}-tab" tabindex="0">
                                        @foreach($thread->messages as $message)
                                            @if ($message->user->first_name.' '.$message->user->last_name == Auth::user()->first_name.' '.Auth::user()->last_name)
                                                    <li class="media d-flex sent">
                                                @else

                                                    <li class="media d-flex received">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="{{ !empty($message->user->userBasicInfo->image) ? asset($message->user->userBasicInfo->image) : asset('assets-v2/img/profiles/avatar-03.jpg') }}" alt="User Image" class="avatar-img rounded-circle">
                                                    </div>
                                                @endif

                                                <div class="media-body flex-grow-1">
                                                    <div class="msg-box">
                                                        <div class="message-sub-box">
                                                            <h4>{{ isset($message->user) ? $message->user->first_name : "" }} {{ isset($message->user) ? $message->user->last_name : "" }}</h4>
                                                                <p>
                                                                    {{ $message->body }}
                                                                    @if ($message->user_id == auth()->user()->id && $message->is_read)
                                                                        <span class="blue-tick">✓✓</span>
                                                                    @endif
                                                                </p>
                                                            @if ($message->image)
                                                                <div class="card rounded-3 overflow-hidden mb-3" >
                                                                    <img src="{{ asset($message->image) }}" alt="Message Photo" class="message-thumbnail card-img-top" id="messageThumbnail{{ $message->id }}" onclick="openLightbox('{{ asset($message->image) }}')" style="display: none">
                                                                </div>
                                                                <ul class="msg-sub-list">
                                                                    <!-- Clickable element to toggle image visibility -->
                                                                    <li onclick="toggleImageVisibility({{ $message->id }})"><img src="{{asset('assets-v2/img/icons/chat-icon-06.svg')}}" alt="" class="me-1">{{ $message->imageName }}<span class="ms-1"></span></li>
                                                                </ul>
                                                            @endif


                                                            <span>{{ \Carbon\Carbon::parse($thread->created_at)->format('h:i A, d M Y') }}</span>

{{--                                                                <form action="{{ route('messages.delete',$message->id)}}" method="POST">--}}
{{--                                                                    @method('DELETE')--}}
{{--                                                                    @csrf--}}
{{--                                                                    @if($message->user_id == auth()->user()->id )--}}
{{--                                                                        <input type="hidden" name="data" value="message">--}}
{{--                                                                        <button type="submit" class="btn btn-link onDeleteThread"><i class="fa fa-trash-alt m-r-5"></i></button>--}}
{{--                                                                    @endif--}}
{{--                                                                </form>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Modal for displaying the image in lightbox -->
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img src="#" id="lightboxImage" class="img-fluid" alt="Image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-footer-box">
                        <div class="discussion-sent">
                            <div class="row gx-2">
                                <div class="col-lg-12 ">
                                    <div id="emojiPanel" style="display: none;">
                                        <!-- Emoji buttons -->
                                        <button class="emoji-button" onclick="insertEmoji('😊')">😊</button>
                                        <button class="emoji-button" onclick="insertEmoji('😂')">😂</button>
                                        <button class="emoji-button" onclick="insertEmoji('😍')">😍</button>
                                        <button class="emoji-button" onclick="insertEmoji('😎')">😎</button>
                                        <button class="emoji-button" onclick="insertEmoji('😘')">😘</button>
                                        <button class="emoji-button" onclick="insertEmoji('😉')">😉</button>
                                        <button class="emoji-button" onclick="insertEmoji('😇')">😇</button>
                                        <button class="emoji-button" onclick="insertEmoji('🥰')">🥰</button>
                                        <button class="emoji-button" onclick="insertEmoji('😋')">😋</button>
                                        <button class="emoji-button" onclick="insertEmoji('😜')">😜</button>
                                        <button class="emoji-button" onclick="insertEmoji('😇')">😇</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤔')">🤔</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙄')">🙄</button>
                                        <button class="emoji-button" onclick="insertEmoji('😔')">😔</button>
                                        <button class="emoji-button" onclick="insertEmoji('😢')">😢</button>
                                        <button class="emoji-button" onclick="insertEmoji('😭')">😭</button>
                                        <button class="emoji-button" onclick="insertEmoji('😱')">😱</button>
                                        <button class="emoji-button" onclick="insertEmoji('😡')">😡</button>
                                        <button class="emoji-button" onclick="insertEmoji('😴')">😴</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤢')">🤢</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤯')">🤯</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤓')">🤓</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤠')">🤠</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤡')">🤡</button>
                                        <button class="emoji-button" onclick="insertEmoji('💩')">💩</button>
                                        <button class="emoji-button" onclick="insertEmoji('👻')">👻</button>
                                        <button class="emoji-button" onclick="insertEmoji('👽')">👽</button>
                                        <button class="emoji-button" onclick="insertEmoji('💀')">💀</button>
                                        <button class="emoji-button" onclick="insertEmoji('👍')">👍</button>
                                        <button class="emoji-button" onclick="insertEmoji('👎')">👎</button>
                                        <button class="emoji-button" onclick="insertEmoji('👌')">👌</button>
                                        <button class="emoji-button" onclick="insertEmoji('👏')">👏</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙌')">🙌</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤝')">🤝</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤟')">🤟</button>
                                        <button class="emoji-button" onclick="insertEmoji('✌️')">✌️</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤘')">🤘</button>
                                        <button class="emoji-button" onclick="insertEmoji('👋')">👋</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤚')">🤚</button>
                                        <button class="emoji-button" onclick="insertEmoji('🖐️')">🖐️</button>
                                        <button class="emoji-button" onclick="insertEmoji('🖖')">🖖</button>
                                        <button class="emoji-button" onclick="insertEmoji('👊')">👊</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤛')">🤛</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤜')">🤜</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤞')">🤞</button>
                                        <button class="emoji-button" onclick="insertEmoji('🦷')">🦷</button>
                                        <button class="emoji-button" onclick="insertEmoji('🦴')">🦴</button>
                                        <button class="emoji-button" onclick="insertEmoji('🦵')">🦵</button>
                                        <button class="emoji-button" onclick="insertEmoji('🦶')">🦶</button>
                                        <button class="emoji-button" onclick="insertEmoji('👁️‍🗨️')">👁️‍🗨️</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧠')">🧠</button>
                                        <button class="emoji-button" onclick="insertEmoji('👀')">👀</button>
                                        <button class="emoji-button" onclick="insertEmoji('👁️')">👁️</button>
                                        <button class="emoji-button" onclick="insertEmoji('👃')">👃</button>
                                        <button class="emoji-button" onclick="insertEmoji('👂')">👂</button>
                                        <button class="emoji-button" onclick="insertEmoji('👅')">👅</button>
                                        <button class="emoji-button" onclick="insertEmoji('👄')">👄</button>
                                        <button class="emoji-button" onclick="insertEmoji('👶')">👶</button>
                                        <button class="emoji-button" onclick="insertEmoji('👧')">👧</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧒')">🧒</button>
                                        <button class="emoji-button" onclick="insertEmoji('👦')">👦</button>
                                        <button class="emoji-button" onclick="insertEmoji('👩')">👩</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧑')">🧑</button>
                                        <button class="emoji-button" onclick="insertEmoji('👨')">👨</button>
                                        <button class="emoji-button" onclick="insertEmoji('👱')">👱</button>
                                        <button class="emoji-button" onclick="insertEmoji('👴')">👴</button>
                                        <button class="emoji-button" onclick="insertEmoji('👵')">👵</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙍')">🙍</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙎')">🙎</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙅')">🙅</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙆')">🙆</button>
                                        <button class="emoji-button" onclick="insertEmoji('💁')">💁</button>
                                        <button class="emoji-button" onclick="insertEmoji('🙋')">🙋</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧏')">🧏</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤦')">🤦</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤷')">🤷</button>
                                        <button class="emoji-button" onclick="insertEmoji('👩‍🦰')">👩‍🦰</button>
                                        <button class="emoji-button" onclick="insertEmoji('👩‍🦱')">👩‍🦱</button>
                                        <button class="emoji-button" onclick="insertEmoji('👩‍🦳')">👩‍🦳</button>
                                        <button class="emoji-button" onclick="insertEmoji('👩‍🦲')">👩‍🦲</button>
                                        <button class="emoji-button" onclick="insertEmoji('👨‍🦰')">👨‍🦰</button>
                                        <button class="emoji-button" onclick="insertEmoji('👨‍🦱')">👨‍🦱</button>
                                        <button class="emoji-button" onclick="insertEmoji('👨‍🦳')">👨‍🦳</button>
                                        <button class="emoji-button" onclick="insertEmoji('👨‍🦲')">👨‍🦲</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧓')">🧓</button>
                                        <button class="emoji-button" onclick="insertEmoji('👴')">👴</button>
                                        <button class="emoji-button" onclick="insertEmoji('👵')">👵</button>
                                        <button class="emoji-button" onclick="insertEmoji('🚶')">🚶</button>
                                        <button class="emoji-button" onclick="insertEmoji('🏃')">🏃</button>
                                        <button class="emoji-button" onclick="insertEmoji('💃')">💃</button>
                                        <button class="emoji-button" onclick="insertEmoji('🕺')">🕺</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤸')">🤸</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤼')">🤼</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤾')">🤾</button>
                                        <button class="emoji-button" onclick="insertEmoji('🏋️')">🏋️</button>
                                        <button class="emoji-button" onclick="insertEmoji('🚴')">🚴</button>
                                        <button class="emoji-button" onclick="insertEmoji('🚵')">🚵</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤹')">🤹</button>
                                        <button class="emoji-button" onclick="insertEmoji('🏇')">🏇</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧘')">🧘</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧗')">🧗</button>
                                        <button class="emoji-button" onclick="insertEmoji('🏄')">🏄</button>
                                        <button class="emoji-button" onclick="insertEmoji('🏊')">🏊</button>
                                        <button class="emoji-button" onclick="insertEmoji('🤽')">🤽</button>
                                        <button class="emoji-button" onclick="insertEmoji('🚣')">🚣</button>
                                        <button class="emoji-button" onclick="insertEmoji('🧖')">🧖</button>
                                        <button class="emoji-button" onclick="insertEmoji('🛀')">🛀</button>
                                        <button class="emoji-button" onclick="insertEmoji('🛌')">🛌</button>
                                        <button class="emoji-button" onclick="insertEmoji('👣')">👣</button>
                                        <button class="emoji-button" onclick="insertEmoji('🦻')">🦻</button>

                                    </div>
                                    <!-- Photo preview container -->
                                    <div id="photoPreviewContainer" style="display: none; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 10px;">
                                        <img id="photoPreview" src="#" alt="Photo Preview" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                    <div class="footer-discussion">
                                        <div class="inputgroups">
                                                @php
                                                    $threadId = "send-message-4657890" ; //this is just a default value used in action
                                                @endphp

                                                <form method="post" action="{{ route('messages.update', $threadId ) }}" enctype="multipart/form-data" >
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" id="currentThreadId" name="thread_id" value="{{ optional($currentThreadId)->id }}">
                                                    <input type="text" name="message" placeholder="Type your Message...">
                                                    <input type="file" id="photoInput" name="image" style="display: none;" onchange="previewPhoto(this);">
                                                    <div class="send-chat position-icon comman-flex" style="background-color: #ffffff;border-radius: 0px">
                                                        <button type="submit" class="text-white" style="background: #234CE3;
                                                        color: #fff;
                                                        border-radius: 8px;
                                                        padding: 7px;" >Send</button>
                                                    </div>
                                                    <div class="symple-text position-icon">
                                                        <ul>
                                                            <li>
                                                                <a href="javascript:;" onclick="document.getElementById('photoInput').click();">
                                                                    <img src="{{asset('assets-v2/img/icons/chat-foot-icon-01.svg')}}" class="me-2" alt="">
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;" onclick="toggleEmojiPanel()">
                                                                    <img src="{{asset('assets-v2/img/icons/chat-foot-icon-02.svg')}}" class="me-2" alt="">
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </form>
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
<style>
.blue-tick {
    color: #0ab705 !important;
    margin-left: 5px;
    font-size: 12px;
}
.message-content {
    display: flex;
    align-items: center; /* Align items vertically in the message content */
}
.message-thumbnail {
    max-width: 100px;
    max-height: 100px;
    margin-top: 5px;
}
</style>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function setThreadId(threadId){
        document.getElementById('currentThreadId').value = threadId;
        // Update the display of chat boxes based on the new thread ID
        updateChatBoxesDisplay();
    }

    function updateChatBoxesDisplay() {
        var currentThreadId = document.getElementById('currentThreadId').value;

        var chatBoxes = document.querySelectorAll('.card.chat-box');

        chatBoxes.forEach(function(chatBox) {
            var threadId = chatBox.getAttribute('data-thread-id');
            console.log(threadId);
            chatBox.style.display = threadId == currentThreadId ? 'block' : 'none';
        });
    }
</script>
<script>
    function markMessagesAsRead(threadId) {
        // Send an AJAX request to mark messages as read
        $.ajax({
            url: '{{ route("messages.markAsRead", [""]) }}/' + threadId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Handle success response
                // Update the unread message count displayed on the UI
                var unreadCount = response.unreadCount;
                var threadId = response.threadId;
                var $unreadCountElement = $('#unread-message-count-' + threadId);

                if (unreadCount > 0) {
                    // Update the unread message count if it's greater than 0
                    $unreadCountElement.html('<div class="chat-user-count" style="display: block"><span>' + unreadCount + '</span></div>');
                } else {
                    // If the unread message count is 0, remove the unread count display
                    $unreadCountElement.empty();
                }
            },
            error: function(xhr, status, error) {
                // Handle error response if needed
                console.error('Error marking messages as read:', error);
            }
        });
    }
</script>
<script>
    // Function to display photo preview
    function previewPhoto(input) {
        var preview = document.getElementById('photoPreview');
        var previewContainer = document.getElementById('photoPreviewContainer');



        // Check if a photo is selected
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                // Set the source of the preview image to the selected photo
                preview.src = e.target.result;
                // Show the photo preview container
                previewContainer.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]); // Read the selected photo as a data URL
        } else {
            // Hide the photo preview container if no photo is selected
            previewContainer.style.display = 'none';
        }
    }
</script>
<script>
    function toggleImageVisibility(messageId) {
        // Get the thumbnail image element
        var thumbnail = document.getElementById('messageThumbnail' + messageId);

        // Toggle visibility of the image
        if (thumbnail.style.display === 'none') {
            thumbnail.style.display = 'block';
        } else {
            thumbnail.style.display = 'none';
        }
    }
    function toggleEmojiPanel() {
        var emojiPanel = document.getElementById('emojiPanel');
        emojiPanel.style.display = emojiPanel.style.display === 'block' ? 'none' : 'block';
    }
    function openLightbox(imageUrl) {
        // Set the image source and show the modal
        $('#lightboxImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
        $('.close-modal-btn').click(function() {
            $('#imageModal').modal('hide');
        });
    }
</script>
<script>
    function insertEmoji(emoji) {
        var input = document.querySelector('input[name="message"]');
        if (input) {
            var start = input.selectionStart;
            var end = input.selectionEnd;
            var text = input.value;
            var newText = text.substring(0, start) + emoji + text.substring(end);
            input.value = newText;
            // Set the cursor position after the inserted emoji
            input.selectionStart = input.selectionEnd = start + emoji.length;
        }
    }
</script>
@endsection
