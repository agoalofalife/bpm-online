<?php
namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Contracts\ServiceProvider;

class ActionsServiceProviders implements ServiceProvider
{

    public function register()
    {
       app()->bind(Read::class, Read::class);
    }
}