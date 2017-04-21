<?php
namespace agoalofalife\bpm\Assistants;


use agoalofalife\bpm\Contracts\Authentication;

class CookieAuthentication implements Authentication
{
    protected $configuration;

    public function setConfig(...$config)
    {
        $this->configuration = $config;
    }

    public function getCookie()
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
}