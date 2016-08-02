<?php
namespace agoalofalife\bpmOnline\Facade;
use Illuminate\Support\Facades\Facade;
/**
 * @see \Illuminate\Config\Repository
 */
class Authentication extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'agoalofalife\bpmOnline\Contract\Authentication';
    }
}
