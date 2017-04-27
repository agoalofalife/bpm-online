<?php

namespace agoalofalife\bpm\Assistants;


use Psr\Http\Message\ResponseInterface;

trait AuthenticationHelper
{
    public function checkResponseUnauthorized(ResponseInterface $response)
    {
        if ( $response->getStatusCode() == 401 && $response->getReasonPhrase() == 'Unauthorized' )
        {
            $this->kernel->authentication();
            $this->query();
        }
        return true;
    }
}