<?php


namespace agoalofalife\bpmOnline\Api;

class Delete extends ApiBpm
{
    /**
     * Type of Request to delete
     * @var string
     */
    protected $HTTP_TYPE = 'DELETE';

    public function __construct($collection, $format)
    {
        parent::__construct($collection, $format);
        $this->getFormatData($format);
    }

    /**
     * Deleting guid
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
     * Running process , specifically bonding query string is stored in the properties URL_CURL
     */
    public function run()
    {
        return $this->curl();
    }
    
    
}