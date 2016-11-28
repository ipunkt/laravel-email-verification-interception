<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        /** @var \Illuminate\Events\Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(\Illuminate\Auth\Events\Registered::class, function ($user) {
            try {
                if (isset($user->email) && !empty($user->email)) {
                    Email::create([
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                }
            } catch (\Exception $e) {
            }
        });
    }
}