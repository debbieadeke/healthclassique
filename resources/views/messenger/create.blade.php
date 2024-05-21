@extends('layouts.app-v2')

@section('content-v2')
    <h1>Compose a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
        {{ csrf_field() }}
        <div class="col-md-6">
            <!-- Subject Form Input -->
            <div class="form-group">
                <label class="control-label">Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="Subject"
                       value="{{ old('subject') }}">
            </div>

            <!-- Message Form Input -->
            <div class="form-group">
                <label class="control-label">Message</label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </div>

            @if($users->count() > 0)
                Send To: <br/>
                <div class="checkbox">
                    @foreach($users as $user)
                        <label title="{!!$user->first_name!!} {!!$user->last_name!!}">
                            &nbsp; <input type="checkbox" name="recipients[]"
                                                                value="{{ $user->id }}"> {!!$user->first_name!!} {!!$user->last_name!!}
                        </label>
                        <br />
                    @endforeach
                </div>
            @endif

            <!-- Submit Form Input -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Submit</button>
            </div>
        </div>
    </form>
@stop
