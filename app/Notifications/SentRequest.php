<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SentRequest extends Notification
{
    use Queueable;
	public $message;
    public $sender;
    public $directory_id;
    public $ticket_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$sender,$directory_id,$ticket_id)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->directory_id = $directory_id;
        $this->ticket_id = $ticket_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            "message" => $this->message,
            "sender_id" => $this->sender,
            "directory_id" => $this->directory_id,
            "ticket_id" => $this->ticket_id,
            //"redirectTo" => 'user/profile/' . base64_encode($this->sender),
            "ukey"   =>  rand(10,10000)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
