<?php
namespace agoalofalife\bpmOnline\Api;

use agoalofalife\bpmOnline\Contract\CreatorData;

class Update extends ApiBpm implements CreatorData
{
    protected $HTTP_TYPE = 'PUT';

    public function __construct($collection, $format)
    {
        parent::__construct($collection, $format);
        $this->getFormatData($format);
    }

    /**
     *  Guid
     * @param $guid
     * @return $this
     */
    public function guid($guid)
    {
        $this->URL_CURL = '';
        $ParameterQuery = '(guid';
        $ParameterQuery.= "'";
        $ParameterQuery.= $guid;
        $ParameterQuery.= "'";
        $ParameterQuery.=')';
        return $this->concatenationUrlCurl($ParameterQuery);
    }

    /**
     *  Start Curl
     *  The $ this-> format to find the type of XML or Json, In accordance with the established class
     * @param null $data
     * @return XmlHandler
     */
    public function run($data = null)
    {
        \Assert\that($data, 'No data for the post request')->notEmpty();
        $prefixClass = ucfirst($this->format)."Handler";
        return $this->curl($prefixClass()->create($data));
    }
}