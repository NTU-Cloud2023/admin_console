<?php

namespace App\Http\Controllers;

use App\Models\UserMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function userMessages($userId)
    {
        // Retrieve user messages based on $userId and order by message_timestamp
        $messages = UserMessage::where('user_id', $userId)
                     ->orderBy('message_timestamp', 'desc') 
                     ->get();
    
        // Mark the retrieved messages as viewed
        // foreach ($messages as $message) {
        //     $message->update(['viewed' => 1]);
        // }
    
        // Return the messages
        return response()->json($messages, 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function sendMessages(Request $request){
        $message = $request -> message;
        $user_id = $request -> user_id;
        UserMessage::create([
            'user_id' => $user_id,
            'message' => $message,
            'message_timestamp' => now()->timestamp
        ]);
        return back()->with('status', '傳送訊息成功。');
    }

    public function closeMessage($messageId)
    {
        $message = UserMessage::where('id', $messageId)
                     ->first();
        $message->update(['viewed' => 1]);
    }
}
