<?php
namespace agoalofalife\Tests\ServiceProviders;


use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\ServiceProviders\ConfigurationServiceProvider;
use agoalofalife\Tests\TestCase;

class ConfigurationServiceProviderTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new ConfigurationServiceProvider();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }

    public function test_register()
    {
        $this->providers->register();
        $this->assertTrue(app()->bound('config'));
    }
}