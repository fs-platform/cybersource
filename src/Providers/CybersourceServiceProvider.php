<?php

namespace Smbear\Cybersource\Providers;

use Illuminate\Support\ServiceProvider;
use Smbear\Cybersource\Cybersource;

class CybersourceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         $this->publishes([
             __DIR__.'/../../config/cybersource.php' => config_path('cybersource.php'),
         ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
         $this->mergeConfigFrom(
             __DIR__.'/../../config/cybersource.php', 'cybersource'
         );

         $this->app->singleton('cybersource',function (){
             return new Cybersource();
         });
    }
}
