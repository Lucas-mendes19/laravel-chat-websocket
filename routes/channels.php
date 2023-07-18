<?php

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    return DB::selectOne('select count(0) as count from room_user where room_id = ? and user_id = ?', [$roomId, $user->id])
        ->count > 0;
});
