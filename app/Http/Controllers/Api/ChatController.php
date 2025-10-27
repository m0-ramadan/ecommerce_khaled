<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\Chat;
use Exception;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    use TranslatableTrait;

    public function fetchMessages(Request $request)
    {
        try {
            $messages = Chat::where('client_id', $request->user()->id)->get();

            return response()->json([
                'status' => true,
                'message' => $this->translate('messages_retrieved'),
                'data' => ChatResource::collection($messages),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $this->translate('failed_to_fetch_messages'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
            ]);

            Chat::create([
                'client_id' => $request->user()->id,
                'message' => $request->message,
                'is_admin' => false,
            ]);

            return response()->json([
                'status' => true,
                'message' => $this->translate('message_sent'),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $this->translate('failed_to_send_message'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function adminReply(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'message' => 'required|string',
            ]);

            Chat::create([
                'client_id' => $request->user_id,
                'admin_id' => auth('admin')->id(),
                'message' => $request->message,
                'is_admin' => true,
            ]);

            return response()->json([
                'status' => true,
                'message' => $this->translate('reply_sent'),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $this->translate('failed_to_send_reply'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}