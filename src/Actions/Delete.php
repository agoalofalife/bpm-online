<?php
namespace agoalofalife\bpm\Actions;

use agoalofalife\bpm\Assistants\ConstructorUrl;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionGet;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;

/**
 * Class Delete
 * @property KernelBpm kernel
 * @property string HTTP_TYPE
 * @property string url
 * @property array data
 * @package agoalofalife\bpm\Actions
 */
class Delete implements Action, ActionGet
{
    use ConstructorUrl;

    protected $kernel;
    protected $url = '?';
    protected $data = [];
    /**
     * Type of Request to delete
     * @var string
     */
    protected $HTTP_TYPE = 'DELETE';

    public function injectionKernel(KernelBpm $bpm)
    {
        $this->kernel = $bpm;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return \agoalofalife\bpm\Contracts\Handler
     */
    public function processData()
    {
        $this->query();
        return $this->kernel->getHandler();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->getData();
    }

    private function query()
    {
        $parameters = str_replace(' ', '%20', $this->url);

        $url        = $this->kernel->getCollection() . $parameters;
        $urlHome    = config($this->kernel->getPrefixConfig() . '.UrlHome');


            $response =  $this->kernel->getCurl()->request($this->HTTP_TYPE, $urlHome . $url,
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
                    'http_errors' => false
                ]);

            $body = $response->getBody();
            $this->kernel->getHandler()->parse($body->getContents());

            if ( $response->getStatusCode() == 401 && $response->getReasonPhrase() == 'Unauthorized' )
            {
                $this->kernel->authentication();
                $this->query();
            }
    }
}