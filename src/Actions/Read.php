<?php
namespace agoalofalife\bpm\Actions;

use agoalofalife\bpm\Contracts\Action;
use agoalofalife\bpm\KernelBpm;
use Assert\Assert;
use Assert\Assertion;
use Assert\AssertionFailedException;

class Read implements Action
{
    protected $kernel;

    /**
     * Type HTTP_TYPE select
     * @var string
     */
    protected $HTTP_TYPE = 'GET';

    protected $url = '';

    public function injectionKernel(KernelBpm $bpm)
    {
        $this->kernel = $bpm;
    }

    /**
     * @return array url -> string , http_type -> string
     */
    public function getData()
    {
        return ['http_type' => $this->HTTP_TYPE, 'url' => $this->url];
    }

    /**
     *  Concatenation Url In Curl
     * @param $newParameters
     * @return $this
     */
    protected function concatenationUrlCurl($newParameters)
    {
        if ($this->url  == '?') {
            $this->url .= $newParameters;
        } elseif ($this->url == '') {
            $this->url .= $newParameters;
        } else {
            $this->url .= '&'.$newParameters;
        }
        return $this;
    }

    /**
     * @param string  mask 00000000-0000-0000-0000-000000000000
     * @return $this
     */
    public function guid($guid)
    {
        try{
            Assertion::regex($guid, '/[0-9]{8}-[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{12}/');
            $ParameterQuery = '(guid'.'\'' . $guid . '\''.')';
            $this->concatenationUrlCurl($ParameterQuery);

            return $this;
        } catch(AssertionFailedException $e) {
            echo "Your guid {$e->getValue()} does not match the mask : 00000000-0000-0000-0000-000000000000";
        }

    }

    /**
     * Request the type of filter
     * Design   filterConstructor allows you to build logical expressions the conditions selecting the desired object .
     * Expressions filterConstructor can be used to reference the properties and literals ,
     * as well as strings, numbers and Boolean expressions (true, false).
     * Expressions $ filter supports arithmetic , logical operations , and operations groups ,
     * strings , date and time of the operation.
     * @documentation
     * @param $strRequest
     * @return $this
     */
    public function filterConstructor($strRequest)
    {
        $ParameterQuery =  '$filter=';
        $ParameterQuery.=  $strRequest;
        $this->concatenationUrlCurl($ParameterQuery);
        return $this;
    }

    /**
     * Service resources can be obtained in the form of sort .
     * @param      $asc
     * @param null $param
     * @return $this
     * @throws \Exception
     */
    public function orderby($asc, $param = null)
    {
        $ParameterQuery = '$orderby=';
        $ParameterQuery.=  ucfirst($asc);

        if ( empty($param) === false ) {
            if ($param != 'desc' && $param != 'asc') {
                throw new \Exception('no valid orderby parameters');
            }
            $ParameterQuery.=  " ".$param;
        }
         $this->concatenationUrlCurl($ParameterQuery);

        return $this;
    }

    /**
     * In bpm'online support the use of parameter $ the skip ,
     * which allows you to query the service resources ,
     * skipping the specified number of entries.
     * @param $skip
     * @return $this
     */
    public function skip($skip)
    {
        Assert::that($skip, 'You must specify a numeric parameter for the amount of the method')->integer();
        $ParameterQuery = '$skip='.$skip;
        $this->concatenationUrlCurl($ParameterQuery);

        return $this;
    }

    /**
     * Restrictions in the sample query
     * If you want the request to return more than 40 records at a time, it can be implemented using the parameter $ top
     * @param $amountMax
     * @return $this
     */
    public function amount($amountMax = null)
    {
        Assert::that($amountMax,'You must specify a numeric parameter for the amount of the method')->integer();
        $ParameterQuery = '$top='.$amountMax;
        $this->concatenationUrlCurl($ParameterQuery);
        return $this;
    }

    protected function curl()
    {
        $parameters = str_replace(' ', '%20', $this->url);
        $url        = config($this->kernel->prefixConfiguration . 'UrlHome') . $this->kernel->getCollection() . $parameters;
        $curl       = curl_init();

        $headers    = [
            $this->protocol,
            $this->Content_type,
            $this->formatCurl,
        ];

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->HTTP_TYPE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, $this->getCookie());
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $Request = curl_exec($curl);
        curl_close($curl);

        dd($Request);
        $this->checkRequestAuth($Request);
        $classHandler = ucfirst($this->format)."Handler";

        return $classHandler()->responceHandler($Request);
    }
}