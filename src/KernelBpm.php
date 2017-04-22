<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\Handlers\JsonHandler;
use agoalofalife\bpm\Handlers\XmlHandler;
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

    protected $handlers = [
      'xml'  => XmlHandler::class,
      'json' => JsonHandler::class,
    ];

    protected $collection;
    protected $currentAction;
    protected $currentHandler;
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

    /**
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @return string
     */
    public function getPrefixConfig()
    {
        return $this->prefixConfiguration;
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

    public function getHandler()
    {
        return $this->currentHandler;
    }

    public function action($action, callable $callback)
    {
        extract($this->splitAction($action));

        if ( Assertion::classExists($action = $this->action[$action])  && Assertion::classExists($handler = $this->handlers[$handler]) )
        {
            $action               =  app()->make( $action );
            $this->currentHandler =  app()->make( $handler );

            $action->injectionKernel($this);
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
            echo "Expected word 'Collection' in parameter method setCollection received : " .  $e->getValue();
        }

        $this->collection = $collection;
    }

    private function splitAction($action)
    {
        $split = explode(':', $action);

        // verification values
        Assertion::between(count($split), 2, 2);
        Assertion::keyExists( $this->action, $split[0]);
        Assertion::keyExists( $this->handlers, $split[1]);

        return ['action' => $split[0], 'handler' => $split[1]];
    }

    private function bootstrapping()
    {
        foreach ($this->serviceProviders as $provider)
        {
            (new $provider)->register();
        }
    }
}