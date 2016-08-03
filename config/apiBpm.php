<?php
return [
/*
|--------------------------------------------------------------------------
| URL API Registration Bpm
|--------------------------------------------------------------------------
| URL for a cookie, for further authentication for API Bpm
|   https://ourSite.bpmonline.com/ServiceModel/AuthService.svc/Login
*/
    'UrlLogin'=>'',

/*
|--------------------------------------------------------------------------
| Login API Registration Bpm
|--------------------------------------------------------------------------
| Login for cookies, for further authentication for API Bpm
|
*/
    
    'Login'=>'',
    
/*
|--------------------------------------------------------------------------
| Password API Registration Bpm
|--------------------------------------------------------------------------
| Password for cookies, for further authentication for API Bpm
|
*/
    
    'Password'=>'',
    
/*
|--------------------------------------------------------------------------
| URL Our Bpm
|--------------------------------------------------------------------------
| Home Address BPM
|   https://ourSite.bpmonline.com/0/ServiceModel/EntityDataService.svc/
*/

    'UrlHome'=>'',

/*
|--------------------------------------------------------------------------
| Namespaces XML API BPM
|--------------------------------------------------------------------------
| Namespaces in BPM API to parse the XML response
|
*/

    'NamespaceAtom'         =>'http://www.w3.org/2005/Atom',
    'NamespaceMetadata'     =>'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata',
    'NamespaceDataservices' =>'http://schemas.microsoft.com/ado/2007/08/dataservices',


/*
|--------------------------------------------------------------------------
| List Namespaces for post request in BPM
|--------------------------------------------------------------------------
|   Namespaces To specify a file in XML
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