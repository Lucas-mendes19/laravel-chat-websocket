<?php

namespace Tests\Feature;

use App\Http\Requests\RoomStoreRequest;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomRepositoryTest extends TestCase
{
    public function test_when_a_room_was_created_its_users_must_also_be_added(): void
    {
        $request = new RoomStoreRequest();
        $request->checkedUsers = [1,2,3];
        $request->name = 'room_teste';

        $room = Room::query()->create([
            'name' => $request->name
        ]);

        $this->assertDatabaseHas('rooms', ['name' => $request->name]);
    }
}
