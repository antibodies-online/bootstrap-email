<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Table
{

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        foreach($xPath->query('//table') as $element) {
            $element->setAttribute('border', 0);
            $element->setAttribute('cellpadding', 0);
            $element->setAttribute('cellspacing', 0);
        }
        return $doc;
    }

}