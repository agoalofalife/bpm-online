<?php

namespace agoalofalife\Tests\ServiceProviders;

use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\ServiceProviders\AuthenticationServiceProvider;
use agoalofalife\Tests\TestCase;

class AuthenticationServiceProviderTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new AuthenticationServiceProvider();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }

    public function test_register()
    {
        $this->providers->register();
        $this->assertTrue(app()->bound(Authentication::class));
    }
}