<?php
return [
/*
|--------------------------------------------------------------------------
| URL API Registration Bpm
|--------------------------------------------------------------------------
| URL для получения куки,для дальнейшей аутентификации,для API Bpm
|
*/
    'UrlLogin'=>'https://ikratkoe.bpmonline.com/ServiceModel/AuthService.svc/Login',

/*
|--------------------------------------------------------------------------
| Login API Registration Bpm
|--------------------------------------------------------------------------
| Login для получения куки,для дальнейшей аутентификации,для API Bpm
|
*/
    
    'Login'=>'api',
    
/*
|--------------------------------------------------------------------------
| Password API Registration Bpm
|--------------------------------------------------------------------------
| Password для получения куки,для дальнейшей аутентификации,для API Bpm
|
*/
    
    'Password'=>'welcome2ik',
    
/*
|--------------------------------------------------------------------------
| URL Our Bpm
|--------------------------------------------------------------------------
| Адрес домашнего BPM
|
*/

    'UrlHome'=>'https://ikratkoe.bpmonline.com/0/ServiceModel/EntityDataService.svc/',

/*
|--------------------------------------------------------------------------
| Namespaces XML API BPM
|--------------------------------------------------------------------------
| Наймспэйс в BPM API для парсинга ответа XML
|
*/

    'NamespaceAtom'         =>'http://www.w3.org/2005/Atom',
    'NamespaceMetadata'     =>'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata',
    'NamespaceDataservices' =>'http://schemas.microsoft.com/ado/2007/08/dataservices',


/*
|--------------------------------------------------------------------------
| List Namespaces for post request in BPM
|--------------------------------------------------------------------------
|
|
*/
    'listNamespaces' => [
        ['xml:base'        => 'http://softex-iis:7503/0/ServiceModel/EntityDataService.svc/'],
        ['xmlns'           => 'http://www.w3.org/2005/Atom'],
        ['xmlns:d'         => 'http://schemas.microsoft.com/ado/2007/08/dataservices'],
        ['xmlns:m'         => 'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata'],
        ['xmlns:georss'    => 'http://www.georss.org/georss'],
        ['xmlns:gml'       => 'http://www.opengis.net/gml'],

    ],

/*
|--------------------------------------------------------------------------
|   Prefix XML document
|--------------------------------------------------------------------------
|   The prefix for insertion into Namespace XML document to be sent to API BPM
|
*/
    'prefixNamespac'       => 'd'
];