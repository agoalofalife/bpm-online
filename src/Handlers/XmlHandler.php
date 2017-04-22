<?php
namespace agoalofalife\bpm\Handlers;

use agoalofalife\bpm\Contracts\Handler;

class XmlHandler implements Handler
{
    /*
    |--------------------------------------------------------------------------
    | Namespaces XML API BPM
    |--------------------------------------------------------------------------
    | Namespaces in BPM API to parse the XML response
    |
    */
    private $namespaces = [
        'NamespaceAtom'         =>'http://www.w3.org/2005/Atom',
        'NamespaceMetadata'     =>'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata',
        'NamespaceDataservices' =>'http://schemas.microsoft.com/ado/2007/08/dataservices',
    ];

    /*
    |--------------------------------------------------------------------------
    | List Namespaces for post request in BPM
    |--------------------------------------------------------------------------
    |   Namespaces To specify a file in XML
    |
    */
    private $listNamespaces = [
            ['xml:base'        => 'http://softex-iis:7503/0/ServiceModel/EntityDataService.svc/'],
            ['xmlns'           => 'http://www.w3.org/2005/Atom'],
            ['xmlns:d'         => 'http://schemas.microsoft.com/ado/2007/08/dataservices'],
            ['xmlns:m'         => 'http://schemas.microsoft.com/ado/2007/08/dataservices/metadata'],
            ['xmlns:georss'    => 'http://www.georss.org/georss'],
            ['xmlns:gml'       => 'http://www.opengis.net/gml'],
    ];

    /*
    |--------------------------------------------------------------------------
    |   Prefix XML document
    |--------------------------------------------------------------------------
    |   The prefix for insertion into Namespace XML document to be sent to API BPM
    |
    */
    private $prefixNamespace = 'd';

    public function getAccept()
    {
        return 'application/atom+xml;type=entry;';
    }

    public function getContentType()
    {
        return 'Content-type: application/atom+xml;type=entry;';
    }

    public function parse($response)
    {

        $this->responceXML       = simplexml_load_string($response);
//        $copyresponceXML         = simplexml_load_string($responseXml);
        self::$Atom              = config('apiBpm.NamespaceAtom');
        self::$Metadata          = config('apiBpm.NamespaceMetadata');
        self::$Dataservices      = config('apiBpm.NamespaceDataservices');

        if (!empty($this->responceXML->message)) {
            $this->handlerError();
        }

        if ($responseXml) {
            $array_vars_list    = get_object_vars($copyresponceXML);
            if (key_exists('content', $array_vars_list)) {
                return $this->arrayOne();
            }
            if (key_exists('workspace', $array_vars_list)) {
                return  $this->workspace();
            } else {
                return $this->arrayMany();
            }
        }
    }
}