<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Mail\ResendTransportFactory;

use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Teach Laravel how to handle the 'brevo' mailer definition
        Mail::extend('brevo', function (array $config) {
            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    $config['key'] ?? env('BREVO_API_KEY')
                    )
                );
            );
        // Register the resend mail driver
        // Mail::extend('resend', function (array $config) {
        //     return (new ResendTransportFactory)->create($config);
        // });

    if (App::environment('production')) {
        URL::forceScheme('https');
    }
    }
}
