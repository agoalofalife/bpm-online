<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\ServiceProviders\ActionsServiceProviders;
use agoalofalife\bpm\ServiceProviders\AuthenticationServiceProvider;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;
use Assert\Assertion;
use Assert\AssertionFailedException;


class KernelBpm
{
    protected $action = [
        'create' =>  Create::class,
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
        ConfigurationServiceProvider::class,
        ActionsServiceProviders::class,
        AuthenticationServiceProvider::class
    ];

    public function __construct()
    {
        $this->bootstrapping();
    }

    public function authentication()
    {
        $auth = app()->make(Authentication::class);
        $auth->setConfig(config($this->prefixConfiguration));
        $auth->auth();
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
            $action =  app()->make( $action );
            call_user_func($callback, $action);
            $this->currentAction = $action;
        }
        return $this;
    }

    /**
     * @return array url -> string , http_type -> string
     *
     */
    public function get()
    {
        // here query in BPM
        return $this->currentAction->getData();
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