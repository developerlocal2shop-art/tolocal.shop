<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrizeCodeVendorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $voucher;

    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }


    public function build()
    {
       return $this->subject('Your voucher is ready: ' . strtoupper($this->voucher['prizecode']))->view('email.vendor_prize_code');
    }
}
