<h4>Add a new message below</h4>
<form action="{{ route('messages.update', $thread->id) }}" method="post">
    {{ method_field('put') }}
    {{ csrf_field() }}

    <!-- Message Form Input -->
    <div class="form-group">

    </div>

    @if($users->count() > 0)
        <div class="checkbox">
            @foreach($users as $user)
                <label title="{{ $user->name }}">
                    &nbsp; <input type="checkbox" name="recipients[]" value="{{ $user->id }}">{{ $user->first_name }}
                </label>
                <br />
            @endforeach
        </div>
    @endif




    <div class="chat-footer-box">
        <div class="discussion-sent">
            <div class="row gx-2">
                <div class="col-lg-12 ">
                    <div class="footer-discussion">
                        <div class="inputgroups">
                            <input type="text" name="message" placeholder="Type your Message here...">

                            <div class="send-chat position-icon comman-flex">
                                <button type="submit" class="btn btn-primary form-control">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


