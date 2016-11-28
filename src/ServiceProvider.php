<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

use DonePM\PackageManager\PackageServiceProvider;
use DonePM\PackageManager\Support\DefinesConfigurations;
use DonePM\PackageManager\Support\DefinesMigrations;
use DonePM\PackageManager\Support\DefinesViews;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Ipunkt\Laravel\EmailVerificationInterception\Mail\ActivateEmail;
use Ipunkt\Laravel\EmailVerificationInterception\Models\Email;

class ServiceProvider extends PackageServiceProvider implements DefinesMigrations, DefinesConfigurations, DefinesViews
{
    /**
     * package path
     *
     * @var string
     */
    private $packagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

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
            $this->packagePath . 'database' . DIRECTORY_SEPARATOR . 'migrations',
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
            $this->packagePath . 'config' . DIRECTORY_SEPARATOR . 'email-verification.php' => 'email-verification.php',
        ];
    }

    /**
     * returns view file paths
     *
     * @return array|string[]
     */
    public function views()
    {
        return [
            $this->packagePath . 'resources' . DIRECTORY_SEPARATOR . 'views',
        ];
    }
}