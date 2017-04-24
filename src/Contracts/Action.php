<?php
namespace agoalofalife\bpm\Contracts;
use agoalofalife\bpm\KernelBpm;

interface Action
{
    public function injectionKernel(KernelBpm $bpm);
    public function processData();
    public function getUrl();
}