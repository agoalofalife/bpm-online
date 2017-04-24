<?php

namespace agoalofalife\bpm\Contracts;


interface ActionGet
{
    /**
     * @return array url -> string , http_type -> string
     */
    public function getData();
}