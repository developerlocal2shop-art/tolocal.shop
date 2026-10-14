<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AcceptRequest extends Notification
{
    use Queueable;
    public $message;
    public $sender;
    public $admin;
    public $ticket_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message,$sender,$admin,$ticket_id)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->admin = $admin;
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
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

     public function toDatabase($notifiable)
    {
        return [
            "message" => $this->message,
            "sender_id" => $this->sender,
            "admin" => $this->admin,
            "ticket_id" => $this->ticket_id,
            "redirectTo" => 'user/profile/' . base64_encode($this->sender),
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
