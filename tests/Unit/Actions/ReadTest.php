<?php


namespace agoalofalife\Tests\Actions;

use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Handlers\XmlHandler;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Config\Repository;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;


class ReadTest extends TestCase
{
    protected $action;
    public function setUp()
    {
        parent::setUp();
        $this->action = new Read();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(Action::class, $this->action);
    }

    public function test_getUrl()
    {
        $this->assertEquals('?', $this->action->getUrl());
    }
    public function test_guid_exception()
    {
        $fakeText  = $this->faker()->word;
        $this->expectExceptionMessage("Value \"$fakeText\" does not match expression.");
        $this->expectException(\Exception::class);
        $this->action->guid($fakeText);
    }

    public function test_guid()
    {
        $this->assertInstanceOf(Read::class, $this->action->guid('00000000-0000-0000-0000-000000000000'));
        $this->assertEquals("(guid'00000000-0000-0000-0000-000000000000')", $this->action->getUrl());
    }

    public function test_filterConstructor()
    {
        $this->action->filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'');
        $this->assertEquals('?$filter=Id eq guid\'00000000-0000-0000-0000-000000000000\'', $this->action->getUrl());
    }

    public function test_orderBy_exception()
    {
        $this->expectExceptionMessage('no valid orderby parameters');
        $this->expectException(\Exception::class);
        $this->action->orderBy('test', 'noValid');
    }

    public function test_OrderBy()
    {
        $this->action->orderBy('Name');
        $this->assertEquals('?$orderby=Name asc', $this->action->getUrl());
    }

    public function test_skip_exception()
    {
        $this->expectExceptionMessage('You must specify a numeric parameter for the amount of the method');
        $this->expectException(InvalidArgumentException::class);
        $this->action->skip('string');
    }

    public function test_count()
    {
        $this->action->count();
        $this->assertEquals('/$count',  $this->action->getUrl());

        $this->action->filterConstructor('Id eq guid\'00000000-0000-0000-0000-000000000000\'');
        $this->assertEquals('/$count&$filter=Id eq guid\'00000000-0000-0000-0000-000000000000\'',  $this->action->getUrl());

    }
    public function test_skip()
    {
        $this->action->skip(10);
        $this->assertEquals('?$skip=10', $this->action->getUrl() );
    }

    public function test_amount_exception()
    {
        $this->expectExceptionMessage('You must specify a numeric parameter for the amount of the method');
        $this->expectException(InvalidArgumentException::class);
        $this->action->amount('string');
    }

    public function test_amount()
    {
        $this->action->amount(10);
        $this->assertEquals('?$top=10', $this->action->getUrl());
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
        $handler->shouldReceive('getContentType')->times(1);
        $handler->shouldReceive('getAccept')->times(1);
        $handler->shouldReceive('parse')->once();
        $response->shouldReceive('getStatusCode')->once();
        $kernel->shouldReceive('getCurl')->once()->andReturn($curl);
        $curl->shouldReceive('request')->once()->andReturn($response);
        $response->shouldReceive('getBody')->once()->andReturn($stream);
        $stream->shouldReceive('getContents')->once()->andReturn('');


        $this->action->processData();
    }
}