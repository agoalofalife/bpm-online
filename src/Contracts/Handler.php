<?php
namespace agoalofalife\bpm\Contracts;


interface Handler
{
    public function getAccept();
    public function getContentType();
    public function parse($response);
}