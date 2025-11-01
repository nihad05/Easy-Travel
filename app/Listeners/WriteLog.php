<?php

namespace App\Listeners;

use App\Events\OrderPlaces;
use Illuminate\Support\Facades\Log;

class WriteLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderPlaces $event)
    {
        Log::info('Listener bir isdeyir!');
    }
}
