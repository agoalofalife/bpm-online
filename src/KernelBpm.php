<?php
namespace agoalofalife\bpm;

use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Actions\Delete;
use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Actions\Update;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\Handler;
use agoalofalife\bpm\Contracts\SourceConfiguration;
use agoalofalife\bpm\Handlers\JsonHandler;
use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\bpm\ServiceProviders\ActionsServiceProviders;
use agoalofalife\bpm\ServiceProviders\AuthenticationServiceProvider;
use agoalofalife\bpm\ServiceProviders\ClientServiceProvider;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;
use Assert\Assertion;
use Assert\AssertionFailedException;


class KernelBpm
{
    protected $action = [
        'create' =>  Create::class,
        'read'   =>  Read::class,
        'update' =>  Update::class,
        'delete' =>  Delete::class,
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
        AuthenticationServiceProvider::class,
        ClientServiceProvider::class,
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
     * @return array
     */
    public function getListActions()
    {
        return $this->action;
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

    /**
     * @return Handler
     */
    public function getHandler()
    {
        return $this->currentHandler;
    }

    /**
     * Set the response handler
     * @param string $typeHandler default xml
     * @return Action
     */
    public function setHandler($typeHandler = 'xml')
    {
        Assertion::keyIsset($this->handlers, $typeHandler);
        $this->currentHandler = app()->make( $this->handlers[$typeHandler] );
        return  $this->currentHandler;
    }

    /**
     * @return Action
     */
    public function getAction()
    {
        return $this->currentAction;
    }

    /**
     * @param $typeAction string
     * @return Action
     */
    public function setAction($typeAction)
    {
        Assertion::keyIsset($this->action, $typeAction);

        $this->currentAction  =  app()->make( $this->action[$typeAction] );

        return $this->currentAction;
    }

    /**
     * Example action parameter 'read:json'
     * @param string $action
     * @param callable $callback
     * @return $this
     */
    public function action($action, callable $callback)
    {

         extract($this->splitAction($action));

         $action  = $this->setAction($action);
         $this->setHandler($handler);

        $action->injectionKernel($this);
        call_user_func($callback, $action);
        $this->currentAction = $action;

        return $this;
    }

    /**
     * @return array url -> string , http_type -> string
     *
     */
    public function get()
    {
        // here query in BPM
        return $this->currentAction->processData();
    }

    /**
     * Set collection for correct query
     * @param string $collection
     * @return mixed
     * @throws \Exception
     */
    public function setCollection($collection)
    {
        try {
            Assertion::regex($collection, '/[A-z]+Collection$/');
        } catch(AssertionFailedException $e) {
           throw new \Exception("Expected word 'Collection' in parameter method setCollection received : " .  $e->getValue());
        }

        return $this->collection = $collection;
    }

    /**
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
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