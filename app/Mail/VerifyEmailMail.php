<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyUrl;
    public $user;

    public function __construct($user, $verifyUrl)
    {
        $this->user = $user;
        $this->verifyUrl = $verifyUrl;
    }

    public function build()
    {
        return $this->subject('Xác nhận tài khoản của bạn')
            ->view('emails.verify-email')
            ->with([
                'user' => $this->user,
                'verifyUrl' => $this->verifyUrl
            ]);
    }
}
