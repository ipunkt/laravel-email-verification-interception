<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class ActivateEmail extends Mailable implements ShouldQueue
{
    /**
     * @var \Ipunkt\Laravel\EmailVerificationInterception\Models\Email
     */
    public $email;

    /**
     * ActivateEmail constructor.
     *
     * @param \Ipunkt\Laravel\EmailVerificationInterception\Models\Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(config('email-verification.activation.subject', 'Activate your account'))
            ->from(config('email-verification.activation.from.email', config('mail.from.address')),
                config('email-verification.activation.from.name', config('mail.from.name')))
            ->view(config('email-verification.activation.view'));
    }
}