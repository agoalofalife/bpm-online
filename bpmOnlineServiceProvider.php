<?php

namespace agoalofalife\bpmOnline;
use Illuminate\Support\ServiceProvider;
use agoalofalife\bpmOnline\Api\XmlHandler;
class bpmOnlineServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/apiBpm.php' => config_path('apiBpm.php')
        ]);
    }

    /**
     * Register any package services.
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/apiBpm.php', 'apiBpm');
    }


}