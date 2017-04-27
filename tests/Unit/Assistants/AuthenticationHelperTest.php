<?php
namespace agoalofalife\Tests\Assistants;


use agoalofalife\bpm\Assistants\AuthenticationHelper;
use agoalofalife\bpm\KernelBpm;
use agoalofalife\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AuthenticationHelperTest extends TestCase
{
    use AuthenticationHelper;
    public $kernel;
    public function test_checkResponseUnauthorized()
    {
        $this->kernel = $this->mock(KernelBpm::class);
        $this->kernel->shouldReceive('authentication')->once();

        $mock = new MockHandler([
            new Response(401, ['body' => 'Unauthorized'])
        ]);

        $handler  = HandlerStack::create($mock);
        $client   = new Client(['handler' => $handler]);
        try{
             $client->request('GET', '/');
        } catch (\Exception $exception){
            $this->checkResponseUnauthorized($exception->getResponse());
        }
    }

    public function query()
    {
        
    }
}