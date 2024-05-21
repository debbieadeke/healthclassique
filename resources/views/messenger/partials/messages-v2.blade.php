@if ($message->user->first_name.' '.$message->user->last_name == Auth::user()->first_name.' '.Auth::user()->last_name)
    <li class="media d-flex sent">
@else
    <li class="media d-flex received">
    <div class="avatar flex-shrink-0">
        <img src="{{ asset('assets-v2/img/user.jpg') }}" alt="User Image" class="avatar-img rounded-circle">
    </div>
@endif
    <div class="media-body flex-grow-1">
        <div class="msg-box">
            <div class="message-sub-box">
                <h4>{{ $message->user->first_name }} {{ $message->user->last_name }}</h4>
                <p style="max-width:100%">{{ $message->body }}</p>
                <span>{{ $message->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</li>
