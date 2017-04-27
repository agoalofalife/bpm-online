<?php

namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\KernelBpm;
use Illuminate\Support\ServiceProvider;

class BpmBaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '../../config/apiBpm.php' => config_path('apiBpm.php')
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->bind('bpm', function(){
           $bpm = new KernelBpm();
           $bpm->setConfigManually('apiBpm', [
               'UrlLogin' => config('apiBpm.UrlLogin'),
               'Login'    => config('apiBpm.Login'),
               'Password' => config('apiBpm.Password'),
               'UrlHome'  => config('apiBpm.UrlHome'),
           ]);
           return $bpm;
       });
    }
}