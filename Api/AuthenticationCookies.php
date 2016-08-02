<?php

namespace agoalofalife\bpmOnline\Api;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use agoalofalife\bpmOnline\Contract\Authentication;
use Assert\Assertion;

/**
 * Cookie for work with API Bpm
 * Class AuthenticationCookies
 * @package App\ApiBpm
 */
class AuthenticationCookies implements Authentication
{
    /**
     * URL Login for registration
     */
    public $urlLogin;

    /**
     * Username for authorization and get Cookie
     */
    public $username;
    /**
     * Password for authorization and get Cookie
     */
    public $password;
    /**
     * Storage Cookie
     */
    private $cookies;

    public function __construct()
    {
        Assertion::notEmpty(config('apiBpm.UrlLogin'), 'Specify in the string configuration for authorization');
        Assertion::notEmpty(config('apiBpm.Login'), 'The configuration is not specified login');
        Assertion::notEmpty(config('apiBpm.Password'), 'The configuration is not specified password');
        $this->urlLogin  = config('apiBpm.UrlLogin');
        $this->username  = config('apiBpm.Login');
        $this->password  = config('apiBpm.Password');
    }

    /**
     * Add cookie in property
     * @param $newCookie
     * @throws \Exception
     */
    public function putCookie($newCookie)
    {
        if (!is_array($newCookie)) {
            throw new \Exception('function putCookie expects parameter Array');
        }
        $this->cookies = collect($newCookie)->first();
    }
    /**
     * Installation of Cookie recording and issuing
     */
    public function UpdateCookie()
    {
        $curl    = curl_init();
        $headers = array(
            "POST  HTTP/1.0",
            "Content-type: application/json"
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_URL, $this->urlLogin);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
            'UserName'      => $this->username,
            'UserPassword'  => $this->password,
            'SolutionName'  => 'TSBpm',
            'TimeZoneOffset'=> '-120',
            'Language'      => 'Ru-ru')));

        $Response = curl_exec($curl);
        curl_close($curl);
        preg_match('~\ \.ASPXAUTH.*?(?=;)~', $Response, $cookies);
        $this->putCookie($cookies);
        $this->writeCookieFileCache();
    }

    /**
     * Get Cookie Cache in storage
     * @return mixed
     */
    public function getCookieCache()
    {
        if (Cache::has('AuthApiBpmCookie')) {
            return Cache::get('AuthApiBpmCookie');
        } else {
            $this->UpdateCookie();
            return Cache::get('AuthApiBpmCookie');
        }
    }
    
    /**
     * Write cookie in cache
     */
    private function writeCookieFileCache()
    {
        $timeLive = Carbon::now()->addMinutes(480);
        Cache::put('AuthApiBpmCookie', $this->cookies, $timeLive);
        Log::info('Получены новые куки'.$this->cookies);
    }
}
