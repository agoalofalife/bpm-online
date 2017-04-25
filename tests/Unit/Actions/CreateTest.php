<?php
namespace agoalofalife\Tests\Actions;


use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;

class CreateTest extends TestCase
{
    protected $creator;
    
    public function setUp()
    {
        parent::setUp();
        $this->creator = new Create();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->creator);
    }

    public function test_injectionKernel()
    {
        $this->creator->injectionKernel(new KernelBpm());
    }
    public function test_setData()
    {
        $this->creator->setData([]);
    }

    public function test_getUrl()
    {
        $this->assertEquals('/', $this->creator->getUrl());
    }
}