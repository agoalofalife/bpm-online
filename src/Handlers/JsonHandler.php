<?php
namespace agoalofalife\bpm\Handlers;


use agoalofalife\bpm\Contracts\Collection;
use agoalofalife\bpm\Contracts\Handler;

/**
 * @property string buildJson
 */
class JsonHandler implements Handler, Collection
{
    use XmlConverter;

    protected $response;
    protected $buildJson;

    private $jsonPrefix     = 'd';
    private $jsonPrefixWord = 'results';
    private $validText      = [];

    public function getAccept()
    {
        return 'application/json;odata=verbose;';
    }

    public function getContentType()
    {
        return 'application/json;odata=verbose;';
    }

    public function parse($parse)
    {
        if ($this->checkIntegrity($parse) === false)
        {
            return [];
        }

        $this->response = $parse;
        $this->validText = json_decode($parse)->{$this->jsonPrefix}->{$this->jsonPrefixWord};
        return $this;
    }

    /**
     * @param $response
     * @return bool
     */
    public function checkIntegrity($response)
    {
       return isset( json_decode($response)->{$this->jsonPrefix} );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->objectToArray($this->validText);
    }

    /**
     * @return string json
     */
    public function toJson()
    {
        return json_encode($this->validText);
    }

    public function getData()
    {
        return $this->validText;
    }

    public function create(array $data)
    {
        if ( empty($data)){
            return $this->buildJson = '{}';
        }
        return $this->buildJson = json_encode($data);
    }

    private function objectToArray($data)
    {
        $result = array();

        foreach ($data as $key => $value) {
            if (gettype($value) == 'object')
            {
                $result[$key] = $this->objectToArray($value);
            } else{
               if (gettype($value) != 'object')
               {
                   $result[$key] = $value;
               } else{
                   $result[$key] = get_object_vars($value);
               }

            }
        }
        return $result;
    }
    
}