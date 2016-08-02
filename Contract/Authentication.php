<?php
namespace agoalofalife\bpmOnline\Contract;
/**
 * Interface Authentication
 * @package agoalofalife\bpmOnline\Contract
 */
interface  Authentication {
    public function putCookie($newCookie);
    public function UpdateCookie();
    public function getCookieCache();
}