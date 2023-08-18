<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Chat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $messageHistory = '';
    public function __construct($messageHistory)
    {
        $this->messageHistory = $messageHistory;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('laravelChat'),
        ];
    }
    public function broadcastAs(): string
    {
        return "chatting";
    }
}
