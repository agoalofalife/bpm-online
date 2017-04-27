<?php
namespace agoalofalife\bpm\Facade;
use Illuminate\Support\Facades\Facade;

class Bpm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bpm';
    }
}
