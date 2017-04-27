<?php

namespace agoalofalife\Tests\Facade;

use agoalofalife\bpm\Facade\Bpm;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;
use Illuminate\Support\Facades\Facade;

class BpmTest extends TestCase
{
    public function test_getFacadeAccessor()
    {
        app()->bind('bpm', KernelBpm::class);
        Facade::setFacadeApplication(app());
        $this->assertInternalType('string', Bpm::setCollection('testCollection'));
        $this->assertEquals('testCollection', Bpm::setCollection('testCollection'));
    }
}