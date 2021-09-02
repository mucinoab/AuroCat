<?php

namespace App\Services;

use Pusher\Pusher;

// Propagates the message to the agents in the web view
function propagate_msj(array $msj_data)
{
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        )
    );

    $pusher->trigger('nuevo-mensaje', 'App\\Events\\Notify', $msj_data);
}
