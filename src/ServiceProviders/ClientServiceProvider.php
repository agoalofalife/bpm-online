<?php
namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\Contracts\ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ClientServiceProvider implements ServiceProvider
{

    public function register()
    {
        app()->bind(ClientInterface::class, Client::class);
    }
}