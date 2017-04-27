<?php
namespace agoalofalife\Tests\ServiceProviders;

use agoalofalife\bpm\KernelBpm;
use agoalofalife\bpm\ServiceProviders\BpmBaseServiceProvider;
use agoalofalife\Tests\TestCase;
use Illuminate\Support\ServiceProvider;
use phpmock\mockery\PHPMockery;

class BpmBaseServiceProviderTest extends TestCase
{
    protected $providers;

    public function setUp()
    {
        parent::setUp();
        $this->providers = new BpmBaseServiceProvider(app());
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->providers);
    }

    public function test_register()
    {
        $this->providers->register();
        $this->assertTrue(app()->bound('bpm'));
        $this->assertInstanceOf(KernelBpm::class, app('bpm'));
    }

    public function test_boot()
    {
        PHPMockery::mock('agoalofalife\bpm\ServiceProviders', "config_path")->once()->with('apiBpm.php');
        $this->providers->boot();
        \Mockery::close();
    }
}