<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class PreviewText
{
    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__.'/../../resources/templates');
        $this->twig = new Environment($loader, ['autoescape' => false]);
    }

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        if (count($xPath->query('//*[contains(concat(" ", @class, " "), " preview ")]')) === 0 && count($xPath->query('//preview')) > 0) {
            /** @var \DOMElement $previewNode */
            $previewNode = $xPath->query('//preview')[0];
            $previewText = $previewNode->nodeValue;
            $length = strlen($previewText);
            if ($length < 278) {
                $previewText .= str_repeat('&#847; &zwnj; &nbsp; ', (278 - $length));
            }

            $template = $this->twig->load('div.html');
            $html = $template->render(['classes' => 'preview', 'contents' => $previewText]);

            $doc2 = new \DOMDocument('1.0', 'UTF-8');
            $doc2->loadHTML(mb_convert_encoding('<html><div>'.$html.'<div></html>', 'HTML-ENTITIES', 'UTF-8'));
            $replacingNode = $doc2->getElementsByTagName('body')[0]->firstChild;

            /** @var \DOMElement $body */
            $body = $doc->getElementsByTagName('body')[0];
            $replace = $doc->importNode($replacingNode, true);
            $body->insertBefore($replace, $body->firstChild);

            $previewNode->parentNode->removeChild($previewNode);
        }
        return $doc;
    }
}