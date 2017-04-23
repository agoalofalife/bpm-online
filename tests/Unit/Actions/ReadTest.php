<?php


namespace agoalofalife\Tests\Actions;

use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\Tests\TestCase;


class ReadTest extends TestCase
{
    protected $action;
    public function setUp()
    {
        parent::setUp();
        $this->action = new Read();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->action);
    }

    public function test_getUrl()
    {
        $this->assertEquals('?', $this->action->getUrl());
    }
    public function test_guid_exception()
    {
        $this->expectExceptionMessage('Your guid ?? does not match the mask : 00000000-0000-0000-0000-000000000000');
        $this->expectException(\Exception::class);
        $this->action->guid('??');
    }

    public function test_guid()
    {
        $this->assertInstanceOf(Read::class, $this->action->guid('00000000-0000-0000-0000-000000000000'));
        $this->assertEquals("(guid'00000000-0000-0000-0000-000000000000')", $this->action->getUrl());
    }


}