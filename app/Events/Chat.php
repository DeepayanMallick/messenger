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
    public $userName = '';
    public $message = '';
    use Dispatchable, InteractsWithSockets, SerializesModels;

 
    public function __construct($userName, $message)
    {
        $this->userName = $userName;
        $this->message = $message;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('laravelChat'),
        ];
    }
    public function broadcastAs(): string
    {
        return "chatting";
    }
}
