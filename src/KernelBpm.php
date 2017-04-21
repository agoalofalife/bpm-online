<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;


class KernelBpm
{
    protected $serviceProviders = [
        ConfigurationServiceProvider::class
    ];

    public function __construct()
    {
        $this->bootstrapping();
    }
    public function loadConfiguration(SourceConfiguration $configuration)
    {
        config()->set($configuration->getName(), $configuration->get());
    }

    public function bootstrapping()
    {
        foreach ($this->serviceProviders as $provider)
        {
            (new $provider)->register();
        }
    }
}