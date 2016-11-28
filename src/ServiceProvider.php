<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

use DonePM\PackageManager\PackageServiceProvider;
use DonePM\PackageManager\Support\DefinesConfigurations;
use DonePM\PackageManager\Support\DefinesMigrations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Ipunkt\Laravel\EmailVerificationInterception\Mail\ActivateEmail;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class ServiceProvider extends PackageServiceProvider implements DefinesMigrations, DefinesConfigurations
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

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /** @var \Illuminate\Events\Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(\Illuminate\Auth\Events\Registered::class, function (Registered $registered) {
            try {
                $user = $registered->user;
                if ($user !== null && isset($user->email) && ! empty($user->email)) {
                    $email = Email::create([
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);

                    Mail::to($user->email)
                        ->queue(new ActivateEmail($email));
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

    /**
     * returns an array of config files with their corresponding config_path(name)
     *
     * @return array
     */
    public function configurationFiles()
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'email-verification.php' => 'email-verification.php',
        ];
    }
}