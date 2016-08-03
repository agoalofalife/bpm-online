<?php

namespace agoalofalife\bpmOnline\Api;
/***
 * Class to retrieve API BPM data
 * @package App\ApiBpm
 */
class Select extends ApiBpm
{
    
    /**
     * Type HTTP_TYPE select
     * @var string
     */
    protected $HTTP_TYPE = 'GET';

    public function __construct($collection,$format)
    {
       parent::__construct($collection,$format);

        $this->getFormatData($format);
    }
    /**
     * Restrictions in the sample query
     * If you want the request to return more than 40 records at a time, it can be implemented using the parameter $ top
     * @param $amountMax
     * @return string
     */
    public function amount($amountMax=null)
    {
        \Assert\that($amountMax,'You must specify a numeric parameter for the amount of the method')->integer();
        $ParameterQuery = '$top='.$amountMax;
        return $this->concatenationUrlCurl($ParameterQuery);
    }

    /**
     * In bpm'online support the use of parameter $ the skip ,
     * which allows you to query the service resources ,
     * skipping the specified number of entries.
     * @param $skip
     * @return Select
     */
    public function skip($skip)
    {
        \Assert\that($skip, 'You must specify a numeric parameter for the amount of the method')->integer();
        $ParameterQuery = '$skip='.$skip;
        return $this->concatenationUrlCurl($ParameterQuery);
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
        if (!empty($param)) {
            if ($param != 'desc' && $param != 'asc') {
                throw new \Exception('no valid orderby parameters');
            }
            $ParameterQuery.=  " ".$param;
        }
        return $this->concatenationUrlCurl($ParameterQuery);
    }
    /**
     * Method for sampling parameters through Select
     * Example $newobject->select()->choose()->orderby()->run();
     * It can not use
     * @param array $parameters
     * @return string
     */
    public function choose($parameters = array())
    {
        $ParameterQuery='select=';
        $counter = 0;
        foreach ($parameters as $parameter) {
            $counter++;
            if ($counter == count($parameters)) {
                $ParameterQuery.= ucfirst($parameter);
            } else {
                $ParameterQuery.= ucfirst($parameter).',';
            }
        }
        return $this->concatenationUrlCurl($ParameterQuery);
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
     * @return Select
     */
    public function filterConstructor($strRequest)
    {
        $ParameterQuery = '$filter=';
        $ParameterQuery.= $strRequest;
        return $this->concatenationUrlCurl($ParameterQuery);
    }

    /**
     * This method in process...
     * Request the type of any
     * @param $strRequest
     */
    public function anyFilter($strRequest)
    {
    }
    /**
     *
     * @param $guid
     * @return Select
     */
    public function guid($guid)
    {
        $this->URL_CURL = '';
        $ParameterQuery = '(guid'.'\''.$guid.'\''.')';
        return $this->concatenationUrlCurl($ParameterQuery);
    }

    /**
     * Start curl
     */
    public function run()
    {
        return $this->curl();
    }
}
