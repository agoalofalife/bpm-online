<?php
namespace agoalofalife\bpm\SourcesConfigurations;


use agoalofalife\bpm\Contracts\SourceConfiguration;
use Assert\Assertion;

class File implements SourceConfiguration
{

    protected $pathToFile;
    protected $name = 'apiBpm';

    /**
     * get array with configuration
     * @return array
     */
    public function get()
    {
        $configuration =  require $this->pathToFile;
        return $configuration;
    }

    /**
     * @param $path string  path to file
     * @return bool
     */
    public function setSource($path)
    {
        if (file_exists($path))
        {
            $this->pathToFile = $path;
        } else{
            Assertion::file($path, 'Local file name is not exist.');
        }
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}