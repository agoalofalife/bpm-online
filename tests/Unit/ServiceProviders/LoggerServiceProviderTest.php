<?php
namespace agoalofalife\Tests\ServiceProviders;


use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\ServiceProviders\LoggerServiceProvider;
use agoalofalife\Tests\TestCase;
use Monolog\Logger;

class LoggerServiceProviderTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new LoggerServiceProvider();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }

    public function test_register()
    {
        $this->providers->register();
        $this->assertTrue(app()->bound(Logger::class));
        $this->assertInstanceOf(Logger::class, app(Logger::class));
    }

}