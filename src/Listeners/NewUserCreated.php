<?php

namespace Ipunkt\Laravel\EmailVerificationInterception\Listeners;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class NewUserCreated implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof User) {
            $email = Email::create([
                'email' => $event->user->email,
            ]);
        }
    }
}