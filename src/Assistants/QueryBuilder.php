<?php
namespace agoalofalife\bpm\Assistants;

use agoalofalife\bpm\Contracts\Authentication;
use GuzzleHttp\TransferStats;
use Monolog\Logger;

trait QueryBuilder
{
    protected $parameters = [];

    public function getCookie()
    {
        $curl = ['curl' => [ CURLOPT_COOKIEFILE => app(Authentication::class)->getPathCookieFile()]];
        $this->merge($curl);
        return $this;
    }

    public function httpErrorsFalse()
    {
        $errorFalse = ['http_errors' => false];
        $this->merge($errorFalse);
        return $this;
    }

    public function headers()
    {
        $headers =
        ['headers' => [
                    'HTTP/1.0',
                    'Accept'       => $this->kernel->getHandler()->getContentType(),
                    'Content-type' => $this->kernel->getHandler()->getAccept(),
                    app(Authentication::class)->getPrefixCSRF()     => app(Authentication::class)->getCsrf()
                    ]
        ];
        $this->merge($headers);
        return $this;
    }

    public function debug()
    {
        $debug  = ['on_stats' => function (TransferStats $stats) {
            app(Logger::class)->debug('api',
                [
                    'time'    =>  $stats->getTransferTime(),
                    'request' =>
                        [
                            'header' => $stats->getRequest()->getHeaders(),
                            'body'   => $stats->getRequest()->getBody()->getContents(),
                            'method' => $stats->getRequest()->getMethod(),
                            'url'    => $stats->getRequest()->getUri()
                        ]
                ]);
        }];
        $this->merge($debug);
        return $this;
    }

    public function get()
    {
        return $this->parameters;
    }

    public function body()
    {
        $body = [
            'body' => $this->kernel->getHandler()->create($this->data)
        ];
         $this->merge($body);
         return $this;
    }

    private function merge(array $newParameters)
    {
        return $this->parameters = array_merge($this->parameters, $newParameters);
    }
}