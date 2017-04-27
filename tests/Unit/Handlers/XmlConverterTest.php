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

        $xml = new SimpleXMLElement('<entry>
                                <parent><one>test</one><two></two><three>test</three><four>test</four></parent>
                                <parent><one>test</one><two>test</two><three>test</three><four>test</four></parent>
                                <parent><one>test</one><two>test</two><three>test</three><four>test</four></parent>
                                 </entry>');

        $arrayEquals =  ["parent" => [
        [
        "one"  => "test",
        "two"  => null,
        "three"=> "test",
        "four" => "test",
       ],
      [
       "one" => "test",
      "two"   => "test",
      "three" => "test",
      "four"  => "test"
    ],
     [
      "one"   => "test",
      "two"   => "test",
      "three" => "test",
      "four"  => "test",
    ]
  ]
];
        $testEmpty = ['test'=> null];
        $this->assertEquals($testEmpty, $this->xmlToArrayRecursive($testEmpty));
        $this->assertEquals($testEmpty, $this->xmlToArray($testEmpty));
        $this->assertEquals($arrayEquals, $this->xmlToArrayRecursive($arrayEquals));
        $this->assertEquals($arrayEquals, $this->xmlToArrayRecursive($xml));
        $this->assertNull($this->xmlToArray(null));
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

        $this->assertNull($this->xmlToArrayRecursive(null));
    }
}