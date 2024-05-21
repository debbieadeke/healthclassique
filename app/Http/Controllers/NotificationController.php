<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $notifications = Notification::where('user_id',$user_id)
            ->orderBy('created_at', 'desc')
            ->take(5) // Limit to 5 notifications
            ->get();


        return response()->json($notifications);
    }
}
