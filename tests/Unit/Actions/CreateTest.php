<?php
namespace agoalofalife\Tests\Actions;


use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Config\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CreateTest extends TestCase
{
    protected $creator;
    
    public function setUp()
    {
        parent::setUp();
        $this->creator = new Create();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->creator);
    }

    public function test_injectionKernel()
    {
        $this->creator->injectionKernel(new KernelBpm());
    }
    public function test_setData()
    {
        $this->creator->setData([]);
    }

    public function test_getUrl()
    {
        $this->assertEquals('/', $this->creator->getUrl());
    }

    public function test_processData()
    {
        $config = $this->mock(new Repository());
        app()->instance('config', $config);

        $kernel = $this->mock(KernelBpm::class);
        $this->creator->injectionKernel($kernel);

        $curl   = $this->mock(Client::class);
        app()->instance(ClientInterface::class, $curl);

        $response  = $this->mock(ResponseInterface::class);
        $stream    = $this->mock(StreamInterface::class);
        $handler   = $this->mock(XmlHandler::class);
        $kernel->shouldReceive('getCollection')->once();
        $kernel->shouldReceive('getPrefixConfig')->once();
        $kernel->shouldReceive('getHandler')->times(8)->andReturn($handler);
        $handler->shouldReceive('getContentType')->times(2);
        $handler->shouldReceive('getAccept')->times(2);
        $handler->shouldReceive('parse')->once();
        $handler->shouldReceive('create')->times(2);
        $response->shouldReceive('getStatusCode')->once();
        $kernel->shouldReceive('getCurl')->once()->andReturn($curl);
        $curl->shouldReceive('request')->once()->andReturn($response);
        $response->shouldReceive('getBody')->once()->andReturn($stream);
        $stream->shouldReceive('getContents')->once()->andReturn('');


        $this->creator->processData();
    }
}