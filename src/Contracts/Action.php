<?php
namespace agoalofalife\bpm\Contracts;


use agoalofalife\bpm\KernelBpm;

interface Action
{
    /**
     * @return array url -> string , http_type -> string
     */
    public function getData();

    public function injectionKernel(KernelBpm $bpm);

    public function query();

    public function getUrl();
}