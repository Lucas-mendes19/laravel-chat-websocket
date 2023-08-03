<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::query()
        ->whereHas('users', function (Builder $b) {
            return $b->where('user_id', '=', auth()->id());
        })
        ->with('users', function ($b) {
            return $b->where('user_id', '!=', auth()->id());
        });

        return response()->json([
            'data' => $rooms->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $roomId)
    {
        $room = Room::where('id', $roomId)
        ->whereHas('users', function (Builder $b) {
            return $b->where('user_id', '=', auth()->id());
        })
        ->with('users', function ($b) {
            return $b->where('user_id', '!=', auth()->id());
        })
        ->with('messages')
        ->with(['messages.user']);

        return response()->json([
            'data' => $room->first()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'checkedUsers' => 'required',
            'name' => 'required',
        ]);
        
        $validated['checkedUsers'][] = auth()->id();

        $room = Room::query()->create([
            'name' => $validated['name']
        ]);

        $room->users()->attach($validated['checkedUsers']);
        
        return to_route('dashboard', ['id' => $room->id]);
    }

    public function destroy(Room $room, Request $request)
    {
        if ($request->has('user')) {
            return $room->users()->detach($request->user);
        }

        $room->delete();
    }
}