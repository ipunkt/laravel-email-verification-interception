<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        /** @var \Illuminate\Events\Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(\Illuminate\Auth\Events\Registered::class, function () {
            dd('registered', func_get_args());
        });
    }
}