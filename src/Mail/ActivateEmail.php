<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Mail;

use Illuminate\Mail\Mailable;

class ActivateEmail extends Mailable
{
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registration.activate');
    }
}