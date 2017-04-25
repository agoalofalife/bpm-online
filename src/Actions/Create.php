<?php
namespace agoalofalife\bpm\Actions;

use agoalofalife\bpm\Assistants\QueryBuilder;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionSet;
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
    use QueryBuilder;

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

        $this->headers()->getCookie()->body()->httpErrorsFalse()->get();
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