<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSended
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $message;
    public $winner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$message,$winner)
    {
        $this->id = $id;
        $this->message = $message;
        $this->winner = $winner;
    }


}
