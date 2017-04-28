<?php
namespace agoalofalife\bpm\Assistants;

/**
 * Class ConstructorUrl
 *
 * @package agoalofalife\bpm\Assistants
 */
trait ConstructorUrl
{
    use VerifyValues;

    /**
     * Concatenation Url In Curl
     * @param $newParameters string
     * @return $this
     */
    protected function concatenationUrlCurl($newParameters)
    {
        if ($this->url  == '?') {
            $this->url .= $newParameters;
        } elseif ($this->url == '') {
            $this->url .= $newParameters;
        } elseif ($this->url == '/') {
            $this->url .= $newParameters;
        }
        else {
            $this->url .= '&'.$newParameters;
        }
        return $this;
    }

    /**
     * Contains guid
     * @param $guid
     * @return $this
     */
    public function guid($guid)
    {
        $this->checkGuId($guid);
        $this->url      = '';
        $ParameterQuery = '(guid';
        $ParameterQuery.= "'";
        $ParameterQuery.= $guid;
        $ParameterQuery.= "'";
        $ParameterQuery.=')';

        return $this->concatenationUrlCurl($ParameterQuery);
    }
}