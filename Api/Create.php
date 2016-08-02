<?php

    namespace agoalofalife\bpmOnline\Api;

    use agoalofalife\bpmOnline\Api\XmlHandler;
    use agoalofalife\bpmOnline\Contract\CreatorData;
    
/**
 * Class Create
 * Class to create a new object in the BPM
 * @package agoalofalife\bpmOnline\Api
 */
class Create extends ApiBpm implements CreatorData
{
    /**
     * Request type to created
     * @var string
     */
    protected $HTTP_TYPE = 'POST';

    /**
     * Create constructor.
     * @param null $collection
     * @param null $format
     */
    public function __construct($collection, $format)
    {
        parent::__construct($collection, $format);
        $this->concatenationUrlCurl($sc = '/');
        $this->getFormatData($format);
    }
    /**
     *  Start Curl
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