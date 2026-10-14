<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $recipientType;

    public function __construct(array $data, $recipientType = 'customer')
    {
        $this->data = $data;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $subject = $this->recipientType === 'vendor'
            ? 'New shopping card offer reply received for ' . ($this->data['offer_title'] ?? 'your offer')
            : 'Your shopping card offer reply has been submitted successfully';

        return $this->subject($subject)->view('email.offer_reply');
    }
}
