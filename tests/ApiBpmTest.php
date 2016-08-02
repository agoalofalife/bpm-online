<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use agoalofalife\bpmOnline\Api\ApiBpm;
class ApiBpmTest extends TestCase
{
    
    protected $api;
    public function setUp()
    {
        parent::setUp();
        $this->api = new ApiBpm('Case', 'xml');
    }

    public function testNotNullObject()
    {
        $this->assertNotNull($this->api, 'Not exist object ApiBpm');
    }

    public function testNotParametersNewObject()
    {
        $this->setExpectedException('Exception');
        $this->api->checkStartParameters();
    }

    public function testNoValidParameters()
    {
        $this->setExpectedException('Exception');
        $this->api->checkStartParameters('Case', 'jsonA');
    }


}
