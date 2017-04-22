<?php
namespace agoalofalife\bpm\Assistants;

use agoalofalife\bpm\Contracts\Authentication;
use GuzzleHttp\Client;

class CookieAuthentication implements Authentication
{
    protected $pathToCookieFile = __DIR__ . '/../resource/cookie.txt';
    protected $configuration;

    public function setConfig(array $config)
    {
        $this->configuration = $config;
    }

    public function authOld()
    {
        $client   = new Client();
        $response =  $client->request('POST', $this->configuration['UrlLogin'], [
            'curl' =>[
                CURLOPT_COOKIEJAR => './cookie.txt',
            ],
            'headers' => [
                "Content-type: application/json",
                "POST  HTTP/1.0"
            ],
            'json' => [
                'UserName'      => $this->configuration['Login'],
                'UserPassword'  => $this->configuration['Password'],
                'SolutionName'  => 'TSBpm',
                'TimeZoneOffset'=> '-120',
                'Language'      => 'Ru-ru'
            ]
        ]);
        $body = $response->getBody();
        dd( $body->getContents());
    }

    public function auth()
    {
        $curl    = curl_init();
        $headers = array(
            "POST  HTTP/1.0",
            "Content-type: application/json"
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_URL,  $this->configuration['UrlLogin']);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->pathToCookieFile);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
            'UserName'      => $this->configuration['Login'],
            'UserPassword'  => $this->configuration['Password'],
            'SolutionName'  => 'TSBpm',
            'TimeZoneOffset'=> '-120',
            'Language'      => 'Ru-ru')));

        $Response = curl_exec($curl);
        curl_close($curl);
//        preg_match('~\ \.ASPXAUTH.*?(?=;)~', $Response, $cookies);
//        $this->putCookie($cookies);
//        $this->writeCookieFileCache();
    }
}