<?php
namespace agoalofalife\bpm\Contracts;


interface SourceConfiguration
{
    /**
     * @return array
     */
    public function get();

    /**
     * @return string
     */
    public function getName();
}
