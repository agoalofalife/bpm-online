<?php
namespace agoalofalife\bpm\Handlers;


use agoalofalife\bpm\Contracts\Handler;

class JsonHandler implements Handler
{

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
        dd($parse);
        dd(json_decode($parse, true));
        dd('parse go');
    }

    /**
     * the integrity check answer
     * @param $response
     * @return
     */
    public function checkIntegrity($response)
    {
        // TODO: Implement checkIntegrity() method.
    }
}