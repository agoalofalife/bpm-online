<?php
namespace agoalofalife\Tests;

use agoalofalife\bpm\Assistants\CookieAuthentication;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\Handler;
use agoalofalife\bpm\KernelBpm;
use Assert\InvalidArgumentException;

class KernelBpmTest extends TestCase
{
    protected $kernel;
    /**  collection  */
    protected $allActions;
    /**  collection  */
    protected $allHandlers;

    public function setUp()
    {
        parent::setUp();
        $this->kernel = new KernelBpm();
        $this->allActions  = collect(['create','read', 'update', 'delete' ]);
        $this->allHandlers = collect(['json', 'xml']);
    }

    public function test_authentication()
    {
        $cookie = $this->mock(CookieAuthentication::class);
        app()->instance(Authentication::class, $cookie );

        $this->kernel->setConfigManually('test', []);
        $cookie->shouldReceive('setConfig')->once();
        $cookie->shouldReceive('auth');

        $this->kernel->authentication();
    }

    public function test_getListActions()
    {
        $this->assertInternalType("array", $this->kernel->getListActions());
    }

    public function test_getPrefixConfig()
    {
        $key = $this->faker()->text();
        $this->kernel->setConfigManually($key, []);
        $this->assertEquals($key, $this->kernel->getPrefixConfig());
    }

    public function test_getHandler()
    {
        $type = $this->allHandlers->random();
        $this->kernel->setHandler($type);
        $this->assertInstanceOf( Handler::class, $this->kernel->getHandler());
    }

    public function test_setHandler_exception()
    {
        $fakeType = $this->faker()->text(5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The element with key \"{$fakeType}\" was not found");
        $this->kernel->setHandler($fakeType);
    }

    public function test_setHandler()
    {
        $type = $this->allHandlers->random();
        $this->assertInstanceOf(Handler::class, $this->kernel->setHandler($type));
    }

    public function test_getAction()
    {
        $typeAction = $this->allActions->random();

        $this->assertInstanceOf(Action::class, $this->kernel->setAction($typeAction));
    }

    public function test_setAction_exception()
    {
        $fakeAction = $this->faker()->text(5);
        $this->expectExceptionMessage("The element with key \"{$fakeAction}\" was not found");
        $this->expectException(InvalidArgumentException::class);
        $this->kernel->setAction($fakeAction);
    }

    public function test_setAction()
    {
        $typeAction = $this->allActions->random();
        $this->assertInstanceOf(Action::class, $this->kernel->setAction($typeAction));
    }

    public function test_action()
    {
        $typeAction  = $this->allActions->random();
        $typeHandler = $this->allHandlers->random();

        $response = $this->kernel->action("$typeAction:$typeHandler", function ($action){});
        $this->assertInstanceOf(KernelBpm::class, $response);
    }

    public function test_get()
    {
        $action     = $this->allActions->random();
        $mockAction = $this->mock($this->kernel->getListActions()[$action]);

        app()->instance($this->kernel->getListActions()[$action], $mockAction);
        $mockAction->shouldReceive('getData')->once();
        $this->kernel->setAction($action);
        $this->kernel->get();

    }

    public function test_setCollection_exception()
    {
        $fakeText = $this->faker()->text(5);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Expected word 'Collection' in parameter method setCollection received : " . $fakeText);
        $this->kernel->setCollection($fakeText);
    }

    public function test_setCollection()
    {
        $fakeText = $this->faker()->word() . 'Collection';
        $this->kernel->setCollection($fakeText);
        $this->assertEquals($fakeText, $this->kernel->setCollection($fakeText));
    }

    public function test_getCollection()
    {
        $this->kernel->setCollection('TestCollection');
        $this->assertEquals('TestCollection', $this->kernel->getCollection());
    }

}