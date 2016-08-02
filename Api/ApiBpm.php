<?php
    namespace agoalofalife\bpmOnline\Api;

use agoalofalife\bpmOnline\Facade\Authentication as CookieBpm;
use Assert\Assertion;
use agoalofalife\bpmOnline\Contract\CreatorData;
/***
 * @documentation BPM https://academy.terrasoft.ru/documents/technic-sdk/7-8-0/rabota-s-obektami-bpmonline-po-protokolu-odata-s-ispolzovaniem-http
 * Class ApiBpm
 * @package App\ApiBpm
 */
class ApiBpm
{

    /**
     * Type HTTP
     * @var string
     */
    protected $HTTP_TYPE ;
    /**
     * Type protocol HTTP
     * @var string
     */
    protected $protocol = 'HTTP/1.0';

    /**
     * Home api address BPM
     * @var
     */
    protected $UrlHome;
    /**
     * Url string variations in dependence HTTP_TYPE
     * @var
     */
    protected $URL_CURL = '?';
    
    /**
     * Collection in API BPM
     * @var null
     */
    public $typeCollection;
    /**
     * Document Type XML | Json
     * @var string
     */
    public $format;
    /**
     * Type Content-type
     * @var
     */
    protected $Content_type;
    /**
     *  Document Type XML | Json fot curl
     * @var
     */
    protected $formatCurl;
    /**
     * Parameters
     * ApiBpm constructor.
     * @param null $collection
     * @param $format
     */
    public function __construct($collection = null, $format = null)
    {
        $this->checkStartParameters($collection, $format);
        $this->format           = strtolower($format);
        $this->typeCollection   = $collection;
        $this->UrlHome          = config('apiBpm.UrlHome');
    }

    /**
     * Checking input parameters for working with API BPM
     * @param $collection
     * @param $format
     */
    public function checkStartParameters($collection, $format)
    {
        $collection = ucfirst($collection);
        $format     = mb_strtolower($format);
        \Assert\that($collection, 'The first parameter expects a string to the type of collection has BPM')->string();
         Assertion::regex($format, '~^json$|^xml$~', 'Expects parameter json or xml');
    }
    /**
     * Get Base URL
     * @return mixed
     */
    protected function getUrlHome()
    {
        return $this->UrlHome;
    }

    /**
     * Get Format type document
     * @param $format
     * @return string
     */
    protected function getFormatData($format)
    {
        $format = strtoupper($format);
        switch ($format) {
            case 'JSON':
                    $this->formatCurl        =  'Accept: application/json;odata=verbose';
                    $this->Content_type      =  'Content-type: application/json;odata=verbose;';
                break;
            case 'XML':
                    $this->formatCurl        =  'application/atom+xml;type=entry;';
                    $this->Content_type      =  'Content-type: application/atom+xml;type=entry;';
                break;
        }
    }

    /**
     * Get Cookie
     * @return mixed
     */
    public function getCookie()
    {
        return CookieBpm::getCookieCache();
    }

    /**
     * Get Protocol
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Get HTTP TYPE
     * @return mixed
     */
    public function getHttpType()
    {
        return $this->HTTP_TYPE;
    }

    /**
     * Get Collection
     * @return string
     */
    public function getCollection()
    {
        return $this->typeCollection.'Collection';
    }

    /**
     * Get address home API BPM
     * @return mixed
     */
    public function getUriHome()
    {
        return $this->UrlHome;
    }

    /**
     *  Concatenation Url In Curl
     * @param $newParameters
     * @return $this
     */
    protected function concatenationUrlCurl($newParameters)
    {
        if ($this->UrlHome  == '?') {
            $this->URL_CURL .= $newParameters;
        } elseif ($this->URL_CURL == '') {
            $this->URL_CURL .= $newParameters;
        } else {
            $this->URL_CURL .= '&'.$newParameters;
        }
        return $this;
    }

    /**
     * Get Object for class Select
     * @return Select
     */
    public function select()
    {

        return new Select($this->getCollection(), $this->format);
    }
    /**
     * Получить обьект для обновления
     * @return Select
     */
    public function update()
    {
        return new Update($this->getCollection(), $this->format);
    }

    /**
     * Get Object for class create
     * @return Select
     */
    public function create()
    {
        return new Create($this->getCollection(), $this->format);
    }
    /**
     * Get Object for class delete
     * @return Select
     */
    public function delete()
    {
        return new Delete($this->getCollection(), $this->format);
    }

    /**
     * Curl query
     * @param null $Data
     * @return XmlHandler
     */
    protected function curl($Data = null)
    {
        $parameters = str_replace(' ', '%20', $this->URL_CURL);
        $url        = $this->UrlHome.$this->typeCollection.$parameters;
        $curl       = curl_init();

        $headers    = [
            $this->protocol,
            $this->Content_type,
            $this->formatCurl,
                      ];

        !empty($Data)?curl_setopt($curl, CURLOPT_POSTFIELDS, $Data):true;
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->HTTP_TYPE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, $this->getCookie());
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $Request = curl_exec($curl);
        curl_close($curl);

        $this->checkRequestAuth($Request);
        $classHandler = ucfirst($this->format)."Handler";

        return $classHandler()->responceHandler($Request);
    }


    /**
     * Get All Collection
     * @return XmlHandler
     */
    public function allCollection()
    {
        $this->HTTP_TYPE = 'GET';
        return   $this->curl();
    }

    /**
     * Validation of BPM API response
     * @param $request
     * @return bool
     */
    private function checkRequestAuth($request)
    {
        if (preg_match('~HTTP Error 401.1 - Unauthorized|Authentication failed~', $request)) {
            CookieBpm::UpdateCookie();
            $this->curl();
        } else {
            return true;
        }
    }
}