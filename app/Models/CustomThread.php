<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cmgmyr\Messenger\Models\Thread as BaseThread;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomThread extends BaseThread
{
    use HasFactory;

    public function userUnreadMessages($userId)
    {
        $messages = $this->messages()->where('user_id', '!=', $userId)->get();

        try {
            $participant = $this->getParticipantFromUser($userId);
        } catch (ModelNotFoundException $e) {
            return collect();
        }

        return $messages->filter(function ($message) use ($participant) {
            // Check if the message is unread (not read by the participant)
            return !$message->is_read;
        });
    }

    public function userUnreadMessagesCount($userId)
    {
        return $this->userUnreadMessages($userId)->count();
    }

}
