<?php

namespace agoalofalife\Tests\ServiceProviders;


use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\Handlers\JsonHandler;
use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\bpm\ServiceProviders\HandlerServiceProvider;
use agoalofalife\Tests\TestCase;

class HandlerServiceProviderTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new HandlerServiceProvider();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }

    public function test_register()
    {
        $this->providers->register();

        $this->assertTrue(app()->bound(JsonHandler::class));
        $this->assertTrue(app()->bound(XmlHandler::class));
    }
}