<?php
namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\Contracts\ServiceProvider;
use Illuminate\Config\Repository;

class ConfigurationServiceProvider implements ServiceProvider
{
    public function register()
    {
        app()->instance('config', new Repository());
    }
}