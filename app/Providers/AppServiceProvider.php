<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Mail;
use Resend\Laravel\Mail\ResendTransportFactory;


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
        // Register the resend mail driver
        // Mail::extend('resend', function (array $config) {
        //     return (new ResendTransportFactory)->create($config);
        // });
    }
}
