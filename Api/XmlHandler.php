<?php


namespace agoalofalife\bpmOnline\Api;

/**
 * A class for working with XML API BPM
 * Class XmlHandler
 * @package App\ApiBpm
 */
class XmlHandler
{
    protected $NameSpacePrefix;
    /**
     * XML Content response
     * @var
     */
    protected $responceXML;
    /**
     * Namespace XML API BPM
     * @var
     */
    public static $Atom;
    /**
     * Namespace XML API BPM
     * @var
     */
    public static $Metadata;

    /**
     * Namespace XML API BPM
     * @var
     */
    public static $Dataservices;
    /**
     * @array
     */
    public $responceRender;

    /**
     * XmlHandler constructor.
     * @param $responseXml
     */
    public function __construct()
    {

    }

    public function responceHandler($responseXml = null)
    {
        $this->responceXML       = simplexml_load_string($responseXml);
        $copyresponceXML         = simplexml_load_string($responseXml);
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
    /**
     *  Get one Element
     * @return mixed
     */
    public function arrayOne()
    {
        return  $this->Addrender($this->responceXML->children(self::$Atom)->content
            ->children(self::$Metadata)
            ->children(self::$Dataservices));
    }

    /**
     * Extraction array in response XML , more element one
     * @return array
     * @throws \Exception
     */
    public function arrayMany()
    {
        $array_cashe = [];
        try {
            foreach ($this->responceXML->children(self::$Atom)->entry as $item) {
                $array_cashe[] =   $item->content->children(self::$Metadata)
                     ->children(self::$Dataservices);
            }
            return  $this->Addrender($array_cashe);
        } catch (\Exeption $e) {
            dd($this->responceXML);
        }
    }

    /**
     * Return All Collection Bpm
     * @throws \Exception
     */
    public function workspace()
    {
        if (!empty($this->responceXML->message->collection->title)) {
                throw new \Exception("responce BPM API : ".
                    $this->responceXML->innererror->message.". ENG :".  $this->responceXML->message);
        }
        foreach ($this->responceXML->workspace->collection as $item) {
            $array_cashe[] = get_object_vars($item->children(self::$Atom))['title'];
        }
            return $this->Addrender($array_cashe);
    }

    /**
     * HandlerError responce document XML API BPM
     */
    public function handlerError()
    {
            throw new \Exception("responce BPM API : ".
                $this->responceXML->innererror->message.". ENG :".$this->responceXML->message);
    }

    /**
     * Add new Data in property @responceRender
     * @param $newData
     * @return $this
     */
    private function Addrender($newData)
    {
        $this->responceRender = $newData;
        return $this;
    }

    /**
     * Get data after handler
     * @return mixed
     */
    public function getData()
    {
        return $this->responceRender;
    }

    /**
     * Get collect Data in proprty @responceRender
     * @return mixed
     */
    public function collectData()
    {
        return collect($this->responceRender);
    }

    /**
     * Xml text for request in Bpm Online
     * @param $data
     * @return string
     */
    public function create($data)
    {
        $NameSpacePrefix = config('apiBpm.prefixNamespac');
        //----------  Base  ----------//
        $dom          = new \DOMDocument('1.0', 'utf-8');
        $entry        = $dom->createElement('entry');
        $dom->appendChild($entry);

        //----------  NameSpaces  ----------//
        foreach (config('apiBpm.listNamespaces') as $key => $value) {
            $xmlBase  = $dom->createAttribute(key($value));
            $entry->appendChild($xmlBase);
            $value    = $dom->createTextNode($value[key($value)]);
            $xmlBase->appendChild($value);
        }
       //----------  <content type="application/xml">  ----------//
        $content      = $dom->createElement('content');
        $entry->appendChild($content);
        $xmlns_dcd    = $dom->createAttribute('type');
        $content->appendChild($xmlns_dcd);
        $valued       = $dom->createTextNode('application/xml');
        $xmlns_dcd->appendChild($valued);
        //----------  properties  ----------//
        $properties   = $dom->createElement('m:properties');
        $content->appendChild($properties);

        foreach ($data as $nameField => $valueField) {
            $element  = $dom->createElement($NameSpacePrefix.':'.$nameField);
            $properties->appendChild($element);
            $valued   = $dom->createTextNode($valueField);
            $element->appendChild($valued);
        }
        return $dom->saveXML();
    }


}