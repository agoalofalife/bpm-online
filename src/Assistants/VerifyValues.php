<?php
namespace agoalofalife\bpm\Assistants;


use Assert\Assertion;

trait VerifyValues
{
    /**
     * Checks for validity GuId
     * @param string
     */
    public function checkGuId($guId)
    {
        Assertion::regex($guId, '/[A-z0-9]{8}-[A-z0-9]{4}-[A-z0-9]{4}-[A-z0-9]{4}-[A-z0-9]{12}/');
    }
}