<?php
namespace agoalofalife\Tests\Handlers;


use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\Tests\TestCase;


class XmlHandlerTest extends TestCase
{
    protected $xml;

    public function setUp()
    {
        parent::setUp();
        $this->xml = new XmlHandler();
    }

    public function test_getAccept()
    {
        $this->assertEquals('', $this->xml->getAccept());
    }

    public function test_getContentType()
    {
        $this->assertEquals('', $this->xml->getContentType());
    }

    public function test_parse_error()
    {
        $this->assertEquals([], $this->xml->parse('<parent><message><one>Have</one></message></parent>'));
    }

    public function test_parse()
    {
        $this->assertInstanceOf(XmlHandler::class, $this->xml->parse('<parent><one>Have</one></parent>'));
    }

    public function test_checkIntegrity_true()
    {
        $xmlNoValid = <<<XML
<?xml version='1.0'?> 
<document>
 <title>Что 40?</title>
 <from>Джо</from>
 <to>Джейн</to>
 <body>
  Я знаю, что это - ответ. В чем заключается вопрос?
 </body>
</document>
XML;
        $this->assertTrue($this->xml->checkIntegrity(simplexml_load_string($xmlNoValid)));
    }

    public function test_checkIntegrity_false()
    {
        $xmlValid= <<<XML
<?xml version='1.0'?> 
<parent>
 <message>Что 40?</message>
 <from>Джо</from>
 <to>Джейн</to>
 <body>
  Я знаю, что это - ответ. В чем заключается вопрос?
 </body>
</parent>
XML;

        $this->assertFalse($this->xml->checkIntegrity(simplexml_load_string($xmlValid)));
    }

    public function test_getData()
    {
        $this->assertEquals([], $this->xml->getData());
    }

    public function test_toArray_empty()
    {
        $this->assertNull($this->xml->toArray());
    }

    public function test_toArray()
    {
        $this->xml->parse(file_get_contents(__DIR__.'/../../fakeFile/test.xml'));
        $this->assertEquals([0 => [ "Id" => "00000000-0000-0000-0000-000000000000","Number" => "SR0000000" ]], $this->xml->toArray());

    }

    public function test_toJson()
    {
        $this->xml->parse(file_get_contents(__DIR__.'/../../fakeFile/test.xml'));
        $this->assertEquals('[{"Id":"00000000-0000-0000-0000-000000000000","Number":"SR0000000"}]', $this->xml->toJson());
    }
}