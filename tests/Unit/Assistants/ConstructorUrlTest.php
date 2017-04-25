<?php
namespace agoalofalife\Tests\Assistants;


use agoalofalife\bpm\Assistants\ConstructorUrl;
use agoalofalife\Tests\TestCase;

class ConstructorUrlTest extends TestCase
{
    use ConstructorUrl;
    protected $url;
    protected $fakeWord;
    protected $template = '00000000-0000-0000-0000-000000000000';
    public function setUp()
    {
        parent::setUp();
        $this->fakeWord = $this->faker()->word;
    }

    public function test_concatenationUrlCurl_with_question_sign()
    {
        $this->url = '?';
        $this->assertInstanceOf(ConstructorUrlTest::class, $this->concatenationUrlCurl($this->fakeWord));
        $this->assertEquals($this->url, "?$this->fakeWord");
    }

    public function test_concatenationUrlCurl()
    {
        $this->url = $this->fakeWord;
        $this->concatenationUrlCurl($this->fakeWord);
        $this->assertEquals("$this->fakeWord&$this->fakeWord", $this->url);
    }

    public function test_guid()
    {
        $this->guid($this->template);
        $this->assertEquals("(guid'00000000-0000-0000-0000-000000000000')",$this->url );
    }
}