<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use agoalofalife\bpmOnline\Api\ApiBpm;

class SelectTest extends TestCase
{
    protected $api;
    public function setUp()
    {
        parent::setUp();
        $this->api = new ApiBpm('Case', 'xml');
    }

    public function testSelect()
    {
        $responce = $this->api->select()->run();
        $this->assertNotEmpty($responce);
    }
}