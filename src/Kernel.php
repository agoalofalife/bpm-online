<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;

class Kernel
{


    protected $serviceProviders = [
        ConfigurationServiceProvider::class
    ];

    public function setConfiguration(SourceConfiguration $configuration)
    {
        config()->set($configuration->getName(), $configuration->get());
    }

    public function bootstrapping()
    {
        foreach ($this->serviceProviders as $provider)
        {
            $provider->register();
        }
    }
}