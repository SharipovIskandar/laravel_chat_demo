<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::where('id', auth()->id())->select([
            'id',
            'name',
            'email',
        ])->first();

        return view('home', [
            'user' => $user,
        ]);
    }

    public function messages(): JsonResponse
    {
        $messages = Message::with('user')->get()->append('time');

        return response()->json($messages);
    }

    public function message(Request $request): JsonResponse
    {
        $receiverId = $request->get('receiver_id'); // POST so'rovdan receiver_idni olish

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'text' => $request->get('text'),
        ]);

        SendMessage::dispatch($message);

        return response()->json([
            'success' => true,
            'message' => "Message created and job dispatched.",
        ]);
    }
    public function logout()
    {
        session()->flush();
    }
}
