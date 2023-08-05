<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Http\Requests\MessageSendRequest;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rooms = Room::query()
            ->whereHas('users', function (Builder $b) {
                return $b->where('user_id', '=', auth()->id());
            })
            ->with('users', function ($b) {
                return $b->where('user_id', '!=', auth()->id());
            });

        return Inertia::render('Chat', [
            'users' => User::where('id', '!=', auth()->id())->get(),
            'rooms' => $rooms->get(),
        ]);
    }

    public function sendMessage(MessageSendRequest $request)
    {
        Message::create([
            'room_id' => $request->room_id,
            'message' => $request->message,
            'user_id' => auth()->id()
        ])->get();

        MessageSentEvent::dispatch($request->room_id);
        
        return response()->json('Menssagem enviada com sucesso.');
    }
}
