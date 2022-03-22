<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\ScssCompiler;

class HeadStyle
{
    private $scssCompiler;

    public function __construct(ScssCompiler $scssCompiler)
    {
        $this->scssCompiler = $scssCompiler;
    }

    public function convert(\DOMDocument $doc)
    {
        $css = $this->scssCompiler->getHeadCss();
//        $css = $this->purgeUnusedCss($css);
        $html = '<html><head><style type="text/css">'.$css.'</style></head><body></body></html>';
        $doc2 = new \DOMDocument('1.0', 'UTF-8');
        $doc2->loadHTML($html);

        $replacingNode = $doc2->getElementsByTagName('head')[0]->firstChild;
        $replace = $doc->importNode($replacingNode, true);
        $xPath = new \DOMXPath($doc);
        $xPath->query('/html/head')[0]->appendChild($replace);
        return $doc;
    }

//    private function purgeUnusedCss(string $css) {
//        list($default, $custom) = explode('/*! allow_purge_after */', $css);
//        $cssDeclaration = [];
//        preg_match('/\w*\.[\w\-]*[\s\S\n]+?(?=})}{1}/', $custom, $cssDeclaration);
//        foreach($cssDeclaration as $group) {
//
//        }
//        return $css;
//    }
}