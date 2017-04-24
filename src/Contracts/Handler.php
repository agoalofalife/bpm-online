<?php
namespace agoalofalife\bpm\Contracts;


interface Handler
{
    public function getAccept();
    public function getContentType();
    public function parse($response);

    /**
     * the integrity check answer
     * @param $response
     * @return
     */
    public function checkIntegrity($response);

    public function create(array $data);
}