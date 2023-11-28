<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlert
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $store_id;
    public $urls;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $store_id)
    {
        $this->message = $message;
        $this->store_id = $store_id;
        $this->urls = $urls;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('stockalert-channel');

    }

    public function broadcastAs()
    {
        return 'stockalert-event-send-meesages';
    }
}
