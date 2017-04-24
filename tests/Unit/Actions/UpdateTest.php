<?php
namespace agoalofalife\Tests\Actions;


use agoalofalife\bpm\Actions\Update;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\Tests\TestCase;
use Assert\InvalidArgumentException;

class UpdateTest extends TestCase
{
    protected $action;
    protected $maskGuiD = '00000000-0000-0000-0000-000000000000';
    public function setUp()
    {
        parent::setUp();
        $this->action = new Update();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->action);
    }

    public function test_guid_exception()
    {
        $fakeText = $this->faker()->word;

        $this->expectExceptionMessage("Value \"{$fakeText}\" does not match expression.");
        $this->expectException(InvalidArgumentException::class);
        $this->action->guid($fakeText);
    }

    public function test_guid()
    {
        $this->action->guid($this->maskGuiD);
        $this->assertEquals("(guid'{$this->maskGuiD}')", $this->action->getUrl());
    }

    public function test_getData()
    {
        $fake = $this->faker()->randomElements();
        $this->action->setData($fake);
        $get = $this->action->getData();
        $this->assertEquals($fake, $get);
        $this->assertInternalType('array', $get);
    }

    public function test_setData()
    {
        $fake = $this->faker()->randomElements();
        $this->assertEquals($fake, $this->action->setData($fake));
    }

    public function test_getUrl()
    {
        $this->assertEquals('?', $this->action->getUrl());
    }
}