<?php

namespace Ipunkt\Laravel\EmailVerificationInterception;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\MailableMailer;
use Ipunkt\Laravel\EmailVerificationInterception\Services\EmailService;
use Ipunkt\Laravel\PackageManager\PackageServiceProvider;
use Ipunkt\Laravel\PackageManager\Support\DefinesConfigurations;
use Ipunkt\Laravel\PackageManager\Support\DefinesMigrations;
use Ipunkt\Laravel\PackageManager\Support\DefinesViews;

class ServiceProvider extends PackageServiceProvider implements DefinesMigrations, DefinesConfigurations, DefinesViews
{
    /**
     * package path
     *
     * @var string
     */
    private $packagePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->app->singleton(EmailService::class, function () {
            /** @var MailableMailer $mailer */
            $mailer = $this->app[MailableMailer::class];

            return new EmailService($mailer);
        });

        if (config('email-verification.activation.register-event-listening', true) === true) {
            $this->registerEventListener();
        }
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

    public function provides()
    {
        return [EmailService::class,];
    }

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
     * registers event listener
     */
    private function registerEventListener()
    {
        /** @var EmailService $emailService */
        $emailService = $this->app[EmailService::class];

        /** @var \Illuminate\Events\Dispatcher $events */
        $events = $this->app['events'];

        $events->listen(Registered::class, function (Registered $registered) use ($emailService) {
            try {
                $user = $registered->user;
                if ($user instanceof Model
                    && isset($user->email)
                    && !empty($user->email)
                ) {
                    $emailService->register($user->getKey(), $user->email);
                }
            } catch (\Exception $e) {
            }
        });
    }
}