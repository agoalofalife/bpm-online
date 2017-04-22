<?php
namespace agoalofalife\bpm\Assistants;

use agoalofalife\bpm\Contracts\Authentication;

/**
 * Class CookieAuthentication
 * @package agoalofalife\bpm\Assistants
 */
class CookieAuthentication implements Authentication
{
    protected $pathToCookieFile = __DIR__ . '/../resource/cookie.txt';
    protected $configuration;

    public function setConfig(array $config)
    {
        $this->configuration = $config;
    }

    public function getPathCookieFile()
    {
        return $this->pathToCookieFile;
    }

    public function refresh()
    {
        $this->auth();
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
            'UserName'       => $this->configuration['Login'],
            'UserPassword'   => $this->configuration['Password'],
            'SolutionName'   => 'TSBpm',
            'TimeZoneOffset' => '-120',
            'Language'       => 'Ru-ru')));

        curl_exec($curl);
        curl_close($curl);

    }
}