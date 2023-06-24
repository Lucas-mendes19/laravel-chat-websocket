<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        // MessageSentEvent::dispatch('ok');

        $room = Room::query()
            ->whereHas('users', function (Builder $b) {
                return $b->where('user_id', '=', auth()->id());
            })
            ->with('users', function ($b) {
                return $b->where('user_id', '!=', auth()->id());
            });

        dd($room->get());

        return Inertia::render('Chat', [
            'users' => User::whereNot('id', auth()->id())->get()
        ]);
    }

    public function createRoom($id)
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
