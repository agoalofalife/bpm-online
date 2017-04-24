<?php

namespace agoalofalife\Tests\Assistants;

use agoalofalife\bpm\Assistants\CookieAuthentication;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\Tests\TestCase;

class CookieAuthenticationTest extends TestCase
{
    protected $authClass;

    public function setUp()
    {
        parent::setUp();
       $this->authClass = new CookieAuthentication();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Authentication::class, $this->authClass);
    }

    public function test_setConfig()
    {
        $this->authClass->setConfig([]);
    }

    public function test_getPathCookieFile()
    {
        $this->assertRegExp('/src\/Assistants\/\.\.\/resource\/cookie\.txt/', $this->authClass->getPathCookieFile());
    }

    public function test_auth_error()
    {
        $this->assertEquals(false, $this->authClass->auth());
    }

    public function test_auth()
    {
        $this->authClass->setConfig([
            'Login'    => '',
            'Password' => '',
            'UrlLogin' => 'https://ikratkoe.bpmonline.com/ServiceModel/AuthService.svc/Login'
        ]);

        $this->assertRegExp('/200 OK/', $this->authClass->auth());
    }

    public function test_getCsrf()
    {
        $this->assertInternalType('string', $this->authClass->getCsrf());
    }
}