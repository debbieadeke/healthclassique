<?php

namespace App\Http\Controllers;

use App\Models\CustomThread;
use App\Models\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Dotenv\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {


        // // All threads, ignore deleted/archived participants
       // $threads = Thread::getAllLatest()->get();
        $threads = CustomThread::forUser(Auth::id())->latest('updated_at')->get();
        $currentThreadId = Thread::getAllLatest()->first('id'); //will be used to set the default thread on the blade

        $unread = 0;
        foreach ($threads as $thread) {
            $unreadCount = $thread->userUnreadMessagesCount(Auth::id());
            $unread = $unread + $unreadCount;
        };



        // All threads that user is participating in
         //$threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        return view('messenger.index-v3', compact('threads', 'unread','currentThreadId'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect()->route('messages');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();

        $thread->markAsRead($userId);

        return view('messenger.show-v2', compact('thread', 'users'));
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    public function markAsRead($threadId)
    {
        try {
            Message::where('thread_id', $threadId)
                ->where('user_id', '!=', auth()->id()) // Exclude messages from the current user
                ->update(['is_read' => true]);

            // Get the count of unread messages for the updated thread
            $unreadCount = CustomThread::find($threadId)->userUnreadMessagesCount(auth()->id());

            // Return the success response along with the unread count
            return response()->json(['success' => true, 'threadId' => $threadId, 'unreadCount' => $unreadCount, 'currentThreadId' => $threadId]);
        } catch (\Exception $e) {
            // If an exception occurs, return an error response
            return response()->json(['error' => 'Failed to mark messages as read.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'message' => 'required|string',
            ]);

            $thread = Thread::create([
                'subject' => $request->input('subject'),
            ]);

            // Message
            $message = new Message();
            $message->thread_id = $thread->id;
            $message->user_id =  Auth::id();
            $message->body = $request->input('message');
            if ($request->hasFile('image')) {
                $originalName = $request->file('image')->getClientOriginalName();
                $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('img/'), $imageName);
                $filePath = 'img/' . $imageName;
                $message->imageName = $originalName;
                $message->image = $filePath;
            }
            $message->save();

            // Sender
            Participant::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'last_read' => new Carbon(),
            ]);

            // Recipients
            if ($request->has('recipients')) {
                $thread->addParticipant($request->input('recipients'));
            }

            return redirect()->route('messages.index')->with('success', 'Message has been successfully sent');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Error Creating Message: ' . $e->getMessage());
        }
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'message' => 'required|string',
            ]);

            if( request()->thread_id ){
                $id = request()->thread_id ;
            }


            try {
                $thread = Thread::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

                return redirect()->route('messages');
            }

            $thread->activateAllParticipants();


            $message = new Message();
            $message->thread_id = $thread->id;
            $message->user_id =  Auth::id();
            $message->body = $request->input('message');
            if ($request->hasFile('image')) {
                $originalName = $request->file('image')->getClientOriginalName();
                $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->move(public_path('img/'), $imageName);
                $filePath = 'img/' . $imageName;
                $message->imageName = $originalName;
                $message->image = $filePath;
            }
            $message->save();



            // Add replier as a participant
            $participant = Participant::firstOrCreate([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
            ]);
            $participant->last_read = new Carbon();
            $participant->save();

            // Recipients
            if ($request->has('recipients')) {
                $thread->addParticipant($request->input('recipients'));
            }
            return redirect()->route('messages.index')->with('success', 'Message has been successfully sent');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Error Updating Message: ' . $e->getMessage());
        }

    }
    public function delete($dataId){
        //dataId can either be the message id or the thread id. used one function to delete both messages and threads
        if( request()->data == "message" ){
            if($message = Message::where('id',$dataId)->where('user_id',Auth::id() )->first() ){
                $message->delete();
            }else{
                return redirect()->back()->with("This message does not exist");
            }
        }else if( request()->data == "thread" ){
            $thread = Thread::find($dataId); //this is the thread id
            $thread->participants()->delete();
            $thread->delete();

            // Thread::destroy($dataId);
        }


        return redirect()->back();
    }
}
