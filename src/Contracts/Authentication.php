<?php
namespace agoalofalife\bpm\Contracts;


interface Authentication
{
    public function setConfig(array $configuration);
    public function auth();
    public function refresh();
    public function getPathCookieFile();
}