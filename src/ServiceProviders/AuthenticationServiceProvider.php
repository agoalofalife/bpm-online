<?php
namespace agoalofalife\bpm\ServiceProviders;

use agoalofalife\bpm\Assistants\CookieAuthentication;
use agoalofalife\bpm\Contracts\Authentication;
use agoalofalife\bpm\Contracts\ServiceProvider;

class AuthenticationServiceProvider implements ServiceProvider
{

    public function register()
    {
        app()->bind(Authentication::class, CookieAuthentication::class);
    }
}