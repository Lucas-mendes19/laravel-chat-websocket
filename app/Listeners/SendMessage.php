<?php

namespace App\Listeners;

use App\Events\MessageSentEvent;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSentEvent $event): void
    {
        Message::create([
            'message' => $event->message
        ]);
    }
}
