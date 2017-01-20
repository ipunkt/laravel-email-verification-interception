# Email verification and mailer interception package for Laravel

Do not send any email to an unverified or blacklisted customer. This package helps solving this task.

[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-email-verification-interception/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-email-verification-interception) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-email-verification-interception/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-email-verification-interception) [![License](https://poser.pugx.org/ipunkt/laravel-email-verification-interception/license.svg)](https://packagist.org/packages/ipunkt/laravel-email-verification-interception) [![Total Downloads](https://poser.pugx.org/ipunkt/laravel-email-verification-interception/downloads.svg)](https://packagist.org/packages/ipunkt/laravel-email-verification-interception)

## Quickstart

```
composer require ipunkt/laravel-email-verification-interception:dev-master
```

Add to `providers` in `config/app.php`:

```
\Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider::class,
```

## Installation

Add to your composer.json following lines

	"require": {
		"ipunkt/laravel-email-verification-interception": "dev-master"
	}

Add `\Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider::class,` to `providers` in `app/config/app.php`.

Run `php artisan vendor:publish --provider="Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider" --tag=config` to publish the config.

We ship an activation mail template. For customizing this run `php artisan vendor:publish --provider="Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider" --tag=view`.

We also provide migration to create a database table. Laravel automatically migrates it. If you want to customize it just run `php artisan vendor:publish --provider="Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider" --tag=migrations`

## Configuration

The main configuration settings handle the activation mail stuff.

### Activation section

#### subject

Mail subject for activation mail. You can change the subject. This subject will be used for the shipped ActivateEmail mailable.

#### from

You have to set your sender data for the activation mail.

#### view

The template view for the activation mail sent.

You can customize this view template content by publishing it to your code base by typing `php artisan vendor:publish --provider="Ipunkt\Laravel\EmailVerificationInterception\ServiceProvider" --tag=view`.

### User Model

Sometimes the user model has a different model class. You can set it here.
