<?php $class = $thread->isUnread(Auth::id()) ? 'alert-info' : ''; ?>

<div class="media alert {{ $class }}" style="border-bottom: 1px solid grey">
    <p class="p-2">
        <strong>From:</strong> {{ $thread->creator()->first_name }} {{ $thread->creator()->last_name }}
    </p>
    <p class="p-2">
        <strong>Subject:</strong> <a href="{{ route('messages.show', $thread->id) }}">{{ $thread->subject }}</a>
        @if ($thread->userUnreadMessagesCount(Auth::id()) >= 1)
            ({{ $thread->userUnreadMessagesCount(Auth::id()) }} unread)
        @endif

    </p>
    <p class="p-2">
        <strong>Time:</strong> {{ $thread->created_at }}
    </p>
</div>
