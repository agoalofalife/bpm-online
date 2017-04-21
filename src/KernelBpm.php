<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;
use Assert\Assertion;
use Assert\AssertionFailedException;


class KernelBpm
{
    protected $action = [
        'create' =>  '',
        'read'   =>  Read::class,
        'update' =>  '',
        'delete' =>  '',
    ];

    protected $collection;
    protected $currentAction;
    protected $url;

    /**
     * prefix name configuration
     */
    protected $prefixConfiguration;

    /**
     * list providers for pre bootstrapping packages
     */
    protected $serviceProviders = [
        ConfigurationServiceProvider::class
    ];

    public function __construct()
    {
        $this->bootstrapping();
    }

    public function loadConfiguration(SourceConfiguration $configuration)
    {
        config()->set(  $this->prefixConfiguration = $configuration->getName(), $configuration->get());
    }

    /**
     * @param       $key
     * @param array $array
     * @return void
     */
    public function setConfigManually($key, array $array)
    {
        $this->prefixConfiguration = $key;
        config()->set($key, $array);
    }

    public function action($action, callable $callback)
    {
        if ( Assertion::classExists($action = $this->action[$action]) )
        {
            call_user_func($callback,  app()->make($this->action[$action]));
        }
    }

    /**
     * @return array url -> string , http_type -> string
     *
     */
    public function get()
    {
        $this->currentAction;
    }

    /**
     * Set collection for correct query
     * @param $collection
     */
    public function setCollection($collection)
    {
        try {
            Assertion::regex($collection, '/[A-z]+Collection$/');
        } catch(AssertionFailedException $e) {
            echo "Expected word 'collection' in parameter method setCollection received : " .  $e->getValue();
        }

        $this->collection = $collection;
    }

    private function bootstrapping()
    {
        foreach ($this->serviceProviders as $provider)
        {
            (new $provider)->register();
        }
    }
}