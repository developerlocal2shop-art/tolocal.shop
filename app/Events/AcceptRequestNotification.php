<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AcceptRequestNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message,$user,$sender,$unreadNotificationCount;
    public function __construct($message,$user,$senderId,$unreadNotificationCount)
    {
        $sender = User::find($senderId);
        $this->message = "<strong>" . $sender->name . " " . $sender->email . " " . "</strong>" . $message;
        $this->user = $user;
        $this->sender = $sender;
        $this->unreadNotificationCount = $unreadNotificationCount;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->user->id);
    }

    public function broadcastAs()
    {
        return 'acceptrequest';
    }
}
