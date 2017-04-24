<?php
namespace agoalofalife\bpm\Actions;


use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\Contracts\ActionSet;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\KernelBpm;
use Assert\Assertion;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

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
        $this->kernel->getHandler();
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function query()
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
                        'Accept'       => $this->kernel->getHandler()->getContentType(),
                        'Content-type' => $this->kernel->getHandler()->getAccept()
                    ],
                    'curl' => [
                        CURLOPT_COOKIEFILE => app()->make(Authentication::class)->getPathCookieFile(),
//                        CURLOPT_POSTFIELDS => $this->kernel->getHandler()->create($this->data)
                    ],
                    'body' => $this->kernel->getHandler()->create($this->data)
                ]);

            $body = $response->getBody();
            dd($body);
            $this->kernel->getHandler()->parse($body->getContents());
        } catch (ClientException $e) {

            dd($e->getMessage());
            if ($e->getResponse()->getStatusCode() == 401 && $e->getResponse()->getReasonPhrase() == 'Unauthorized')
            {
                $this->kernel->authentication();
                return $this->query();
            }
        }

    }
}