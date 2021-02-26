<?php

namespace TwoCheckOut;

use Illuminate\Support\ServiceProvider;

class TwoCheckoutServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            // Config file.
            __DIR__ . '/config/2checkout.php' => config_path('2checkout.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 2checkout Facede.
        $this->app->singleton('TwoCheckout', function () {
            return new TwoCheckOut;
        });
    }
}
