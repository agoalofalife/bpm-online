<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use agoalofalife\bpmOnline\Api\AuthenticationCookies;


class AuthenticationCookiesTest extends TestCase
{
    protected $cookies;
    public function setUp()
    {
        parent::setUp();
        $this->cookies = new AuthenticationCookies();
    }

    public function testPropertyConfig()
    {
        $this->assertNotNull($this->cookies->urlLogin);
        $this->assertNotNull($this->cookies->username);
        $this->assertNotNull($this->cookies->password);
    }

    public function testGetCookie()
    {
        $this->assertNotEmpty($this->cookies->getCookieCache());
    }

}