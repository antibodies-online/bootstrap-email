<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class MetaTag
{
    private $metaTags = [
        '/html/head/meta[@http-equiv="Content-Type"]' => '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
        '/html/head/meta[@http-equiv="x-ua-compatible"]' => '<meta http-equiv="x-ua-compatible" content="ie=edge">',
        '/html/head/meta[@name="-apple-disable-message-reformatting"]' => '<meta name="x-apple-disable-message-reformatting">',
        '/html/head/meta[@name="viewport"]' => '<meta name="viewport" content="width=device-width, initial-scale=1">',
        '/html/head/meta[@name="format-detection"]' => '<meta name="format-detection" content="telephone=no, date=no, address=no, email=no">'
    ];

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        foreach($this->metaTags as $query => $code) {
            if (count($xPath->query($query)) === 0) {
                $doc2 = new \DOMDocument('1.0', 'UTF-8');
                $doc2->loadHTML(mb_convert_encoding('<html><head>'.$code.'</head><body></body></html>', 'HTML-ENTITIES', 'UTF-8'));

                $replacingNode = $doc2->getElementsByTagName('head')[0]->firstChild;
                $replace = $doc->importNode($replacingNode, true);

                $xPath->query('/html/head')[0]->appendChild($replace);
            }
        }
        return $doc;
    }

}