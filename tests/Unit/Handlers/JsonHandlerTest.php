<?php

namespace agoalofalife\Tests\Handlers;

use agoalofalife\bpm\Handlers\JsonHandler;
use agoalofalife\Tests\TestCase;

class JsonHandlerTest extends TestCase
{
    protected $jsonHandler;
    protected $templateValid = '{"d" : {  "results" : { "test" : "test" }}}';
    public function setUp()
    {
        parent::setUp();
        $this->jsonHandler = new JsonHandler();
    }

    public function test_getAccept()
    {
        $this->assertEquals('application/json;odata=verbose;', $this->jsonHandler->getAccept());
    }

    public function test_getContentType()
    {
        $this->assertEquals('application/json;odata=verbose;', $this->jsonHandler->getContentType());
    }

    public function test_parse_empty()
    {
        $empty = $this->jsonHandler->parse('{"test" : "test"}');
        $this->assertEquals([], $empty);
    }

    public function test_parse()
    {
        $empty = $this->jsonHandler->parse($this->templateValid);
        $this->assertInstanceOf(JsonHandler::class, $empty);
    }

    public function test_checkIntegrity_true()
    {
        $this->assertTrue($this->jsonHandler->checkIntegrity($this->templateValid));
    }

    public function test_checkIntegrity_false()
    {
        $this->assertFalse($this->jsonHandler->checkIntegrity('{}'));
    }

    public function test_toArray()
    {
        $this->jsonHandler->parse($this->templateValid);
        $this->assertEquals(["test" => "test"], $this->jsonHandler->toArray());
    }

    public function test_toJson()
    {
        $this->jsonHandler->parse($this->templateValid);
        $this->assertEquals('{"test":"test"}', $this->jsonHandler->toJson());
    }

    public function test_getData()
    {
        $this->jsonHandler->parse($this->templateValid);
        $this->assertEquals($this->jsonHandler->getData(),  json_decode($this->templateValid)->d->results);
    }

    public function test_create()
    {
        $this->assertJson($this->jsonHandler->create([]));
        $this->assertJson(($this->jsonHandler->create(["test" => "test"])));
    }
}