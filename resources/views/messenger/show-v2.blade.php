@extends('layouts.app-full-screen-v2')

@section('content-v2')
    <div class="col-md-12">
        <div class="card chat-message-box">
            <div class="card-body p-0">
                <h1>{{ $thread->subject }}</h1>

                

                <div class="chat-body">
                    <ul class="list-unstyled chat-message">
                        @each('messenger.partials.messages-v2', $thread->messages, 'message')

                    </ul>
                </div>


            </div>
        </div>



    </div>

    <div class="chat-footer-box">
        <form action="{{ route('messages.update', $thread->id) }}" method="post">
            {{ method_field('put') }}
            {{ csrf_field() }}
            <div class="discussion-sent">
                <div class="row gx-2">
                    <div class="col-lg-12 ">
                        <div class="footer-discussion">
                            <div class="inputgroups">
                                <input name="message" type="text" placeholder="Type your Message herecslsn...">


                                <div class="send-chat position-icon comman-flex" style="background-color: #ffffff;border-radius: 0px">
                                    <button type="submit" class="text-white" style="background: #234CE3;
                                    color: #fff;
                                    border-radius: 8px;
                                    padding: 7px;" >Send</button>
                                </div>


{{-- 
                                <div class="send-chat position-icon comman-flex">
                                    <button type="submit" class="">Send</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
