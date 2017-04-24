<?php
namespace agoalofalife\bpm\Assistants;

/**
 * Class ConstructorUrl
 *
 * @package agoalofalife\bpm\Assistants
 */
trait ConstructorUrl
{
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
        } else {
            $this->url .= '&'.$newParameters;
        }
        return $this;
    }
}