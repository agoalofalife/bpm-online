<?php
namespace agoalofalife\Tests\ServiceProviders;

use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Actions\Delete;
use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Actions\Update;
use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\ServiceProviders\ActionsServiceProviders;
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
        $this->assertTrue(app()->bound(Create::class));
        $this->assertTrue(app()->bound(Update::class));
        $this->assertTrue(app()->bound(Delete::class));
    }

}