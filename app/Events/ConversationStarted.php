<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $name;
    public $message;
    public $update_id;
    public $transmitter;
    public $date;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$name,$message,$update_id,$transmitter,$date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->message = $message;
        $this->update_id = $update_id;
        $this->transmitter = $transmitter;
        $this->date = $date;

    }
}
