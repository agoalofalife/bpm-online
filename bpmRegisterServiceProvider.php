<?php
namespace agoalofalife\bpmOnline;

use agoalofalife\bpmOnline\Api;
use Illuminate\Support\ServiceProvider;

class bpmRegisterServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any package services.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('agoalofalife\bpmOnline\Contract\Authentication', 'agoalofalife\bpmOnline\Api\AuthenticationCookies');
    }

    public function provides()
    {
        return ['agoalofalife\bpmOnline\Contract\Authentication'];
    }
}