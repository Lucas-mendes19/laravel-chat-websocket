<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(int $roomId)
    {
        $room = Room::find($roomId)
            ->with('messages')
            ->with('messages', function ($b) {
                return $b->with('user');
            });
        return response()->json([
            'data' => $room->first()
        ]);
    }
}
