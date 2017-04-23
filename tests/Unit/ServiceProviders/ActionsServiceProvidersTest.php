<?php
namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\Tests\TestCase;

class ActionsServiceProvidersTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new ActionsServiceProviders();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }
    public function test_register()
    {
        $this->providers->register();
        $this->assertTrue(app()->bound(Read::class));
    }

}