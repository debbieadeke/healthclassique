<?php $class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; ?>

<div class="chat-user-group d-flex align-items-center">
    <div class="img-users call-user">
        <a href="#"><img src="assets-v2/img/user.jpg" alt="img"></a>
        <span class="active-users bg-info"></span>
    </div>
    <div class="chat-users">
        <div class="user-titles d-flex">
            <h5> <a href="#" onclick="openInIframe('{{ route('messages.show', $thread->id) }}')">{{ $thread->creator()->first_name }} {{ $thread->creator()->last_name }}</a>
            </h5>
            <div class="chat-user-time">
                <p>{{ $thread->created_at }}</p>
            </div>
        </div>
        <div class="user-text d-flex">
            <p>{{ $thread->subject }}</p>
            <div class="chat-user-count" style="display: none">
                <span>3</span>
            </div>
        </div>
    </div>
</div>
