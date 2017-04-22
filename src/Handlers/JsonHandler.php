<?php
namespace agoalofalife\bpm\Handlers;


use agoalofalife\bpm\Contracts\Handler;

class JsonHandler implements Handler
{

    public function getAccept()
    {
        return 'Accept: application/json;odata=verbose';
    }

    public function getContentType()
    {
        return 'Content-type: application/json;odata=verbose;';
    }

    public function parse($parse)
    {
        // TODO: Implement parse() method.
    }
}