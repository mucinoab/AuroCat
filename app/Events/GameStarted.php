<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $name;
    public $message;
    public $update_id;
    public $date;
    public $board_state;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$name,$message,$update_id,$date,$board_state)
    {
        $this->id = $id;
        $this->name = $name;
        $this->message = $message;
        $this->update_id = $update_id;
        $this->date = $date;
        $this->board_state = $board_state;
    }

}
