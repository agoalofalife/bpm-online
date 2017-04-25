<?php
namespace agoalofalife\bpm\ServiceProviders;

use agoalofalife\bpm\Contracts\ServiceProvider;
use agoalofalife\bpm\KernelBpm;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class LoggerServiceProvider implements ServiceProvider
{

    public function register()
    {
        app()->bind(Logger::class, function(){
            $log = new Logger(KernelBpm::class);
           return  $log->pushHandler(new StreamHandler(KernelBpm::PATH_LOG . date('Y-m-d'). '.txt', Logger::DEBUG));
        });
    }
}