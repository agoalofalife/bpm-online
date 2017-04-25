<?php
namespace agoalofalife\bpm\Actions;

use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionSet;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;


/**
 * Class Create
 * Class to create a new object in the BPM
 *
 * @property KernelBpm kernel
 * @property string HTTP_TYPE
 * @property array data
 * @package agoalofalife\bpm\Actions
 */
class Create implements Action, ActionSet
{
    protected $kernel;
    protected $url = '/';
    /**
     * Request type to created
     * @var string
     */
    protected $HTTP_TYPE = 'POST';
    protected $data = [];

    public function injectionKernel(KernelBpm $bpm)
    {
        $this->kernel = $bpm;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function processData()
    {
        $this->query();
        return $this->kernel->getHandler();
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    private function query()
    {
        $parameters = str_replace(' ', '%20', $this->url);

        $url        = $this->kernel->getCollection() . $parameters;
        $urlHome    = config($this->kernel->getPrefixConfig() . '.UrlHome');

        $response   =  $this->kernel->getCurl()->request($this->HTTP_TYPE, $urlHome . $url,
                [
                    'headers' => [
                        'HTTP/1.0',
                        'Accept'       => $this->kernel->getHandler()->getAccept(),
                        'Content-type' => $this->kernel->getHandler()->getContentType(),
                        app()->make(Authentication::class)->getPrefixCSRF()     => app()->make(Authentication::class)->getCsrf(),
                    ],
                    'curl' => [
                        CURLOPT_COOKIEFILE => app()->make(Authentication::class)->getPathCookieFile(),
                    ],
                    'body' => $this->kernel->getHandler()->create($this->data),
                    'http_errors' => false
                ]);
        $body       = $response->getBody();
        $this->kernel->getHandler()->parse($body->getContents());
    }
}