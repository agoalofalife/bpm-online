<?php
namespace agoalofalife\bpm\Handlers;


use SimpleXMLElement;

trait XmlConverter
{
    private function xmlToArrayRecursive($xml) {
        $xml = (array) $xml;

        if(empty($xml)) {
            return null;
        }

        foreach ($xml as $key => $val) {
            if (is_array($val)){
                $xml[$key] = $this->xmlToArray($val);
            } else {
                if ($val instanceof SimpleXMLElement) {
                    $xml[$key] = $this->xmlToArray($val);
                }
                // TODO not smog to find a similar case
//                elseif (empty($val)) {
//                    $xml[$key] = null;
//                }
            }

        }

        return $xml;
    }

    private function xmlToArray($xml) {
        $xml = (array) $xml;

        if(empty($xml)) {
            return null;
        }

        foreach ($xml as $key=>$val) {
            if ($val instanceof SimpleXMLElement) {
                $xml[$key] = $this->xmlToArray($val);
            }
            // TODO not smog to find a similar case
//            elseif (empty($val)) {
//                $xml[$key] = null;
//            }
        }

        return $xml;
    }
}