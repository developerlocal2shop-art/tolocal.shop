<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftVoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    public $voucher;
  
    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }

    
    public function build()
    {
       return $this->subject('Enjoy your gift voucher. Code: ' . strtoupper($this->voucher['GiftVoucheData']->voucher_code))->view('email.voucher_code');
    }
}
