<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\Mailer\Event\MessageEvent;

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
        $room = Room::find($request->id)
            ->whereHas('users', function (Builder $b) {
                return $b->where('user_id', '=', auth()->id());
            })
            ->with('users', function ($b) {
                return $b->where('user_id', '!=', auth()->id());
            })
            ->with('messages')
            ->with('messages', function ($b) {
                return $b->with('user');
            });;

        return Inertia::render('Chat', [
            'users' => User::where('id', '!=', auth()->id())->get(),
            'room' => $room->first()
        ]);
    }

    public function sendMessage(Request $request)
    {
        Message::create([
            'room_id' => $request->roomId,
            'message' => $request->message,
            'user_id' => auth()->id()
        ])->get();


        MessageSentEvent::dispatch($request->roomId);
    }

    public function createRoom(int $id)
    {
        $room = Room::query()->create();
        $room ->users()->attach([$id, auth()->id()]);
    }

    public function getRoom($id)
    {
        return $room = Room::query()
            ->whereHas('users', function (Builder $b) {
                return $b->where('user_id', '=', auth()->id());
            })
            ->with('users', function ($b) {
                return $b->where('user_id', '!=', auth()->id());
            });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
