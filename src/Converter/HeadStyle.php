<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\ScssCompiler;

class HeadStyle
{
    private $scssCompiler;

    public function __construct(ScssCompiler $scssCompiler)
    {
        $this->scssCompiler = $scssCompiler;
    }

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        list($defaultCss, $customCss) = explode('/*! allow_purge_after */', $this->scssCompiler->getHeadCss());
        // Get each Css declaration
        preg_match_all('/\w*\.[\w\-]*[\s\S\n]+?(?=})}{1}/', $customCss, $customCssGroups);
        foreach($customCssGroups[0] as $customCssGroup) {
            preg_match_all('/(\.[\w\-]*).*?((,+?)|{+?)/', $customCssGroup, $customCssSelector);
            $foundOccurence = false;
            foreach($customCssSelector[1] as $cssSelector) {
                if(count($xPath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' my-class ')]")) > 0) {
                    $foundOccurence = true;
                }
            }
            if(!$foundOccurence) {
                $customCss = str_replace($customCssGroup, '', $customCss);
            }
        }

        $css = preg_replace('/\n\s*\n+/', '\n', $defaultCss . $customCss);

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