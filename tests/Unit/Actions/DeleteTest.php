<?php
namespace agoalofalife\Tests\Actions;


use agoalofalife\bpm\Actions\Delete;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\Tests\TestCase;
use InvalidArgumentException;

class DeleteTest extends TestCase
{
    protected $action;
    protected $maskGuiD = '00000000-0000-0000-0000-000000000000';
    public function setUp()
    {
        parent::setUp();
        $this->action = new Delete();
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

    public function test_getUrl()
    {
        $this->assertEquals('?', $this->action->getUrl());
    }

}