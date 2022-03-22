<?php

namespace DemigodCode\BootstrapEmail\Converter;

class VersionComment
{

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        $comment = new \DOMComment('Compiled with Bootstrap Email (PHP)');
        $replace = $doc->importNode($comment, true);
        $xPath->query('/html/head')[0]->appendChild($replace);
        return $doc;
    }
}