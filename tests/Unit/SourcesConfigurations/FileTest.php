<?php

namespace agoalofalife\Tests\SourcesConfigurations;

use agoalofalife\bpm\SourcesConfigurations\File;
use agoalofalife\Tests\TestCase;
use Assert;

class FileTest extends TestCase
{
    protected $file;

    public function setUp()
    {
        parent::setUp();
        $this->file = new File();
    }

    public function test_get()
    {
        $this->file->setSource($_SERVER['PWD'] . '/'. 'tests/fakeFile/config.php');
        $this->assertEquals( $this->file->get(), []);
        $this->assertEquals('array', gettype($this->file->get()));
    }

    public function test_get_name()
    {
        $this->assertInternalType('string', $this->file->getName());
    }

    public function test_set_source()
    {
        $this->assertTrue($this->file->setSource($_SERVER['PWD']));
    }

    public function test_set_source_exception()
    {
        $this->expectExceptionMessage('Local file name is not exist.');
        $this->expectException(Assert\InvalidArgumentException::class);
        $this->file->setSource($this->faker()->text('20'));
    }
}