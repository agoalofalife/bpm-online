<?php
namespace agoalofalife\bpm\Actions;



use agoalofalife\bpm\Assistants\ConstructorUrl;
use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionGet;
use agoalofalife\bpm\Contracts\ActionSet;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

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
    use ConstructorUrl;

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
     * @return \agoalofalife\bpm\Contracts\Handler
     */
    public function processData()
    {
        $this->getData();
        return $this->kernel->getHandler();
    }

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     *  Guid
     * @param $guid string
     * @return $this
     */
    public function guid($guid)
    {
        $this->url      = '';
        $ParameterQuery = '(guid';
        $ParameterQuery.= "'";
        $ParameterQuery.= $guid;
        $ParameterQuery.= "'";
        $ParameterQuery.=')';

        return $this->concatenationUrlCurl($ParameterQuery);
    }

    /**
     * @return array url -> string , http_type -> string
     */
    public function getData()
    {
        $this->query();
    }

    private function query()
    {
        $parameters = str_replace(' ', '%20', $this->url);

        $url        = $this->kernel->getCollection() . $parameters;
        $client     = app()->make(ClientInterface::class);
        $urlHome    = config($this->kernel->getPrefixConfig() . '.UrlHome');


        try {
            $response = $client->request($this->HTTP_TYPE, $urlHome . $url,
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
                    'body' => $this->kernel->getHandler()->create($this->data)
                ]);

            $body = $response->getBody();

            $this->kernel->getHandler()->parse($body->getContents());
        } catch (ClientException $e) {

            if ($e->getResponse()->getStatusCode() == 401 && $e->getResponse()->getReasonPhrase() == 'Unauthorized')
            {
                $this->kernel->authentication();
                return $this->query();
            }
        }
    }

}