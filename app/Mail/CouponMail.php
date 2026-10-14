<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coupon;
  
    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    
    public function build()
    {
       // return $this->subject('Coupon Offer')->view('email.shoppingcard_offer');
       return $this->subject('Enjoy your exclusive discount. Coupon Code: ' . strtoupper($this->coupon['voucher_code']))->view('email.coupon');
    }
}
