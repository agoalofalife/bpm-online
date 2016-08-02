<?php
use agoalofalife\bpmOnline\Api\XmlHandler;
use agoalofalife\bpmOnline\Api\JsonHandler;

if (! function_exists('XmlHandler')) {
    /**
     * Just return object XmlHandler
     * @return XmlHandler
     */
    function XmlHandler()
    {
        return new XmlHandler();
    }
}


if (! function_exists('JsonHandler')) {
    /**
     * Just return object XmlHandler
     * @return XmlHandler
     */
    function JsonHandler()
    {
        return new JsonHandler();
    }
}
