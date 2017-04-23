<?php
namespace agoalofalife\Tests\Handlers;


use agoalofalife\bpm\Handlers\XmlConverter;
use agoalofalife\Tests\TestCase;
use SimpleXMLElement;

class XmlConverterTest extends TestCase
{
    use XmlConverter;
    public function setUp()
    {
        parent::setUp();
    }

    public function test_xmlToArray()
    {
        $xml = new SimpleXMLElement('<parent><one>test</one></parent>');
        $this->assertEquals(["one" => "test"], $this->xmlToArray($xml));
    }

    public function test_xmlToArrayRecursive()
    {
        $xml = new SimpleXMLElement('<parent><one>test</one><two><child><paramters>One</paramters></child></two></parent>');
        $this->assertEquals([
            "one" => "test",
            "two" =>  [
                "child"     =>  [
                "paramters" => "One"
                ]
              ]
            ],  $this->xmlToArrayRecursive($xml)
        );

    }
}