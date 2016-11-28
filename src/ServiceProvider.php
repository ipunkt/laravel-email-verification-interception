<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

use DonePM\PackageManager\PackageServiceProvider;
use DonePM\PackageManager\Support\DefinesMigrations;
use Illuminate\Auth\Events\Registered;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class ServiceProvider extends PackageServiceProvider implements DefinesMigrations
{
    /**
     * returns namespace of package
     *
     * @return string
     */
    protected function namespace()
    {
        return 'email-verification';
    }

    public function boot()
    {
        parent::boot();

        /** @var \Illuminate\Events\Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(\Illuminate\Auth\Events\Registered::class, function (Registered $registered) {
            try {
                $user = $registered->user;
                if ($user !== null && isset($user->email) && ! empty($user->email)) {
                    Email::create([
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                }
            } catch (\Exception $e) {
            }
        });
    }

    /**
     * returns an array of migration paths
     *
     * @return array|string[]
     */
    public function migrations()
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
        ];
    }
}