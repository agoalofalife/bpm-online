<?php
namespace agoalofalife\Tests\Assistants;


use agoalofalife\bpm\Assistants\CookieAuthentication;
use agoalofalife\bpm\Assistants\QueryBuilder;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use Monolog\Logger;

class QueryBuilderTest extends TestCase
{
    use QueryBuilder;
    protected $kernel;

    public function setUp()
    {
        parent::setUp();
    }

    public function test_getCookie()
    {
        $auth = $this->mock(CookieAuthentication::class);
        app()->instance(Authentication::class, $auth);

        $auth->shouldReceive('getPathCookieFile')->once();
        $this->getCookie();
    }

    public function test_httpErrorsFalse()
    {
        $parameterError = [
            "http_errors" => false
        ];
        $httpError = $this->httpErrorsFalse()->get();
       $this->assertInternalType('array', $httpError);
       $this->assertEquals($parameterError, $httpError);
    }

    public function test_headers()
    {
        $this->kernel = new KernelBpm();
        $this->kernel->setHandler('xml');
        $auth = $this->mock(CookieAuthentication::class);
        app()->instance(Authentication::class, $auth);

        $auth->shouldReceive('getPrefixCSRF')->once();
        $auth->shouldReceive('getCsrf')->once();

        $this->headers();
    }

    public function test_debug()
    {
        $logger = $this->mock(Logger::class);
        app()->instance(Logger::class, $logger);

        $this->assertInternalType('object',  $this->debug());
        $this->assertInstanceOf(QueryBuilderTest::class, $this->debug());
    }

    public function test_get()
    {
        $this->parameters  = $this->faker()->word;
        $this->assertEquals($this->parameters, $this->get());
    }

    public function test_debug_in_process()
  {
      $logger = $this->mock(Logger::class);
      app()->instance(Logger::class, $logger);
      $logger->shouldReceive('debug')->once();

      $mock = new MockHandler([
          new Response(200, ['X-Foo' => 'Bar']),
          new Response(202, ['Content-Length' => 0]),
          new RequestException("Error Communicating with Server", new Request('GET', 'test'))
      ]);

      $handler = HandlerStack::create($mock);
      $client = new Client(['handler' => $handler]);
      $client->request('GET', $this->faker()->url, $this->debug()->get());
  }
}