<?php
namespace agoalofalife\Tests\Assistants;

use agoalofalife\bpm\Assistants\VerifyValues;
use agoalofalife\Tests\TestCase;
use Assert\InvalidArgumentException;

class VerifyValuesTest extends TestCase
{
    use VerifyValues;
    public function setUp()
    {
        parent::setUp();
    }

    public function test_checkGuId()
    {
        $fakeErrorString = $this->faker()->word;
        $this->expectExceptionMessage("Value \"{$fakeErrorString}\" does not match expression.");
        $this->expectException(InvalidArgumentException::class);
        $this->checkGuId( $fakeErrorString );
    }
}