<?php
namespace agoalofalife\Tests\Actions;


use agoalofalife\bpm\Actions\Delete;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\KernelBpmTest;
use agoalofalife\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Config\Repository;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class DeleteTest extends TestCase
{
    protected $action;
    protected $maskGuiD = '00000000-0000-0000-0000-000000000000';
    public function setUp()
    {
        parent::setUp();
        $this->action = new Delete();
    }

    public function test_injectionKernel()
    {
        $this->action->injectionKernel(new KernelBpm());
    }
    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->action);
    }

    public function test_guid_exception()
    {
        $fakeText = $this->faker()->word;

        $this->expectExceptionMessage("Value \"{$fakeText}\" does not match expression.");
        $this->expectException(InvalidArgumentException::class);
        $this->action->guid($fakeText);
    }

    public function test_guid()
    {
        $this->action->guid($this->maskGuiD);
        $this->assertEquals("(guid'{$this->maskGuiD}')", $this->action->getUrl());
    }

    public function test_getUrl()
    {
        $this->assertEquals('?', $this->action->getUrl());
    }

    public function test_processData()
    {
        $config = $this->mock(new Repository());
        app()->instance('config', $config);

        $kernel = $this->mock(KernelBpm::class);
        $this->action->injectionKernel($kernel);

        $curl   = $this->mock(Client::class);
        app()->instance(ClientInterface::class, $curl);

        $response  = $this->mock(ResponseInterface::class);
        $stream    = $this->mock(StreamInterface::class);
        $handler   = $this->mock(XmlHandler::class);
        $kernel->shouldReceive('getCollection')->once();
        $kernel->shouldReceive('getPrefixConfig')->once();
        $kernel->shouldReceive('getHandler')->times(4)->andReturn($handler);
        $handler->shouldReceive('getContentType')->once();
        $handler->shouldReceive('getAccept')->once();
        $handler->shouldReceive('parse')->once();

        $kernel->shouldReceive('getCurl')->once()->andReturn($curl);
        $curl->shouldReceive('request')->once()->andReturn($response);
        $response->shouldReceive('getStatusCode')->once();
        $response->shouldReceive('getBody')->once()->andReturn($stream);
        $stream->shouldReceive('getContents')->once()->andReturn('');


        $this->action->processData();
    }
}