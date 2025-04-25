<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
  use Queueable, SerializesModels;

  public $user;
  public $otp;

  public function __construct($user, $otp)
  {
    $this->user = $user;
    $this->otp = $otp;
  }

  public function build()
  {
    return $this->subject('Your OTP for Registration')
      ->view('emails.send_otp')
      ->with(['otp' => $this->otp]);
  }
}
