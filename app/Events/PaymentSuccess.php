<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentSuccess implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;
    public $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting PaymentSuccess event', ['order_id' => $this->transaction->external_id]);
        return new Channel('payments');
    }

    public function broadcastAs()
    {
        return 'payment-success';
    }
}