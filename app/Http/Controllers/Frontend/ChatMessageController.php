<?php

namespace App\Http\Controllers\Frontend;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatMessageController extends Controller
{
    public function index()
    {
        return view('frontend.pages.chat.index');
    }

    public function getMessages()
    {
        $messages = ChatMessage::with('user:id,name')
            ->orderBy('id', 'desc')
            ->limit(100) // get 100 latest messages
            ->get()
            ->reverse() // re-order collection in ascending order
            ->values();

        return [
            'messages' => $messages
        ];
    }

    public function sendMessage(Request $request)
    {
        $success = FALSE;

        if ($request->message) {
            $user = auth()->user();

            // store message
            $message = new ChatMessage();
            $message->user()->associate($user);
            $message->message = $request->message;
            $message->save();

            broadcast(new ChatMessageSent($message))->toOthers();

            $success = TRUE;
        }

        return [
            'success' => $success
        ];
    }
}
