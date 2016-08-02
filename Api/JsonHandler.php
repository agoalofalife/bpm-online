<?php

namespace agoalofalife\bpmOnline\Api;

/**
 * Class of processing JSON format
 * Class JsonHandler
 * @package App\ApiBpm
 */
class JsonHandler
{
    /**
     * The container with the data in json
     * @var null
     */
    protected $ContainerJson;
    /**
     * Storage json data
     * @var
     */
    public $json;

    public function __construct()
    {
//        if(!json_decode($Request)->d)
//        {
//            throw new \Exception("delegat d now isset return json");
//        }
//        $this->responseJson = json_decode($Request)->d;
//        return $this->responseJson;
    }

    public function responceHandler($Request = null)
    {
        $this->ContainerJson = $Request;
        $this->json          = $Request;
        return $this;
    }
    /**
     * Get format json
     * @return mixed
     */
    public function json()
    {
        return $this->json;
    }
    /**
     * Returns an array of samples
     * @return mixed
     */
    public function toArray()
    {
        return json_decode($this->ContainerJson)->d;
    }



    /**
     * Converting data to be sent in JSON format
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return json_encode($data);
    }

    /**
     * Returns an array of collection
     * @return mixed
     */
    public function collectData()
    {
        return collect(json_decode($this->ContainerJson)->d);
    }
}