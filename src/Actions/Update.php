<?php
namespace agoalofalife\bpm\Actions;

use agoalofalife\bpm\Assistants\ConstructorUrl;
use agoalofalife\bpm\Assistants\QueryBuilder;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionGet;
use agoalofalife\bpm\Contracts\ActionSet;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;

/**
 * Class Update
 * @property KernelBpm kernel
 * @property string HTTP_TYPE
 * @property array data
 * @property string url
 * @package agoalofalife\bpm\Actions
 */
class Update implements Action, ActionGet, ActionSet
{
    use ConstructorUrl, QueryBuilder;

    protected $url = '?';

    /**
     * Request type to created
     * @var string
     */
    protected $HTTP_TYPE = 'PUT';
    protected $data = [];
    protected $kernel;

    /**
     * @param KernelBpm $bpm
     * @return void
     */
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
     * @param array $data
     * @return array
     */
    public function setData(array $data)
    {
        return $this->data = $data;
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
        return $this->data;
    }

    /**
     * @return mixed
     */
    private function query()
    {
        $parameters = str_replace(' ', '%20', $this->url);
        $url        = $this->kernel->getCollection() . $parameters;
        $urlHome    = config($this->kernel->getPrefixConfig() . '.UrlHome');

        $response   =  $this->kernel->getCurl()->request($this->HTTP_TYPE, $urlHome . $url,
                       $this->headers()->getCookie()->body()->httpErrorsFalse()->get()
        );
        $body       = $response->getBody();

        $this->kernel->getHandler()->parse($body->getContents());

        if ( $response->getStatusCode() == 401 && $response->getReasonPhrase() == 'Unauthorized' )
        {
            $this->kernel->authentication();
            $this->query();
        }
    }

}