<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\MailableMailer;
use Ipunkt\Laravel\EmailVerificationInterception\Mail\ActivateEmail;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class EmailService
{
    /**
     * @var \Illuminate\Mail\MailableMailer
     */
    private $mailer;

    /**
     * constructing EmailService
     *
     * @param \Illuminate\Mail\MailableMailer $mailer
     */
    public function __construct(MailableMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * register a new email address for a user
     *
     * @param Model|int $user
     * @param string $email
     * @param Mailable $activationMail
     */
    public function register($user, string $email, Mailable $activationMail = null)
    {
        $userId = ($user instanceof Model)
            ? $user->getKey()
            : $user;

        if ($existingEmail = Email::whereEmail($email)->first() !== null) {
            return;
        }

        $email = Email::create([
            'user_id' => $userId,
            'email' => $email,
        ]);

        $activateMail = ($activationMail === null)
            ? new ActivateEmail($email)
            : $activationMail;

        $this->mailer->to($email)
            ->queue($activateMail);
    }
}