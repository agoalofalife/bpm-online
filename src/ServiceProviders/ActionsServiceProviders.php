<?php
namespace agoalofalife\bpm\ServiceProviders;


use agoalofalife\bpm\Actions\Create;
use agoalofalife\bpm\Actions\Delete;
use agoalofalife\bpm\Actions\Read;
use agoalofalife\bpm\Actions\Update;
use agoalofalife\bpm\Contracts\ServiceProvider;

class ActionsServiceProviders implements ServiceProvider
{

    public function register()
    {
       app()->bind(Read::class,   Read::class);
       app()->bind(Create::class, Create::class);
       app()->bind(Update::class, Update::class);
       app()->bind(Delete::class, Delete::class);
    }
}