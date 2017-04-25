<?php

namespace agoalofalife\Tests;

class helpersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_config()
    {
        $this->assertTrue(function_exists('config'));
    }

    public function test_app()
    {
        $this->assertTrue(function_exists('app'));
    }
}