<?php

namespace AntibodiesOnline\BootstrapEmail;

use AntibodiesOnline\BootstrapEmail\Converter\Alert;
use AntibodiesOnline\BootstrapEmail\Converter\Align;
use AntibodiesOnline\BootstrapEmail\Converter\Badge;
use AntibodiesOnline\BootstrapEmail\Converter\Block;
use AntibodiesOnline\BootstrapEmail\Converter\Body;
use AntibodiesOnline\BootstrapEmail\Converter\Button;
use AntibodiesOnline\BootstrapEmail\Converter\Card;
use AntibodiesOnline\BootstrapEmail\Converter\Container;
use AntibodiesOnline\BootstrapEmail\Converter\Grid;
use AntibodiesOnline\BootstrapEmail\Converter\HeadStyle;
use AntibodiesOnline\BootstrapEmail\Converter\Hr;
use AntibodiesOnline\BootstrapEmail\Converter\Image;
use AntibodiesOnline\BootstrapEmail\Converter\Margin;
use AntibodiesOnline\BootstrapEmail\Converter\MetaTag;
use AntibodiesOnline\BootstrapEmail\Converter\Padding;
use AntibodiesOnline\BootstrapEmail\Converter\PreviewText;
use AntibodiesOnline\BootstrapEmail\Converter\Spacer;
use AntibodiesOnline\BootstrapEmail\Converter\Spacing;
use AntibodiesOnline\BootstrapEmail\Converter\Table;
use AntibodiesOnline\BootstrapEmail\Converter\VersionComment;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Compiler
{
    private $converters = [];
    private $configurators = [];
    private $cssConverters = [];
    private $scssCompiler;
    private $twig;

    public function __construct(ScssCompiler $scssCompiler)
    {
        $this->converters[] = new Body();
        $this->converters[] = new Block();

        $this->converters[] = new Button();
        $this->converters[] = new Badge();
        $this->converters[] = new Alert();
        $this->converters[] = new Card();
        $this->converters[] = new Hr();
        $this->converters[] = new Container();
        $this->converters[] = new Grid();
//        $this->converters[] = new Stack();

//        $this->converters[] = new Color();
        $this->converters[] = new Spacing();
        $this->converters[] = new Margin();
        $this->converters[] = new Spacer();
        $this->converters[] = new Align();
        $this->converters[] = new Padding();
        $this->converters[] = new PreviewText();
        $this->converters[] = new Table();

        $this->configurators[] = new HeadStyle($scssCompiler);
        $this->configurators[] = new MetaTag();
        $this->configurators[] = new VersionComment();

        $this->cssConverters[] = new Image();
        $this->scssCompiler = $scssCompiler;
    }

    public function compileHtml(string $html, string $pathHeadScss = '', string $pathScss = '') {
        $document = new \DOMDocument('1.0', 'UTF-8');
        \libxml_use_internal_errors(true);
        $document->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        \libxml_clear_errors();
        $this->compile($document, $pathHeadScss, $pathScss);
        return $this->HtmlPostProcessor($document->saveHTML());
    }

    public function compile(\DOMDocument $document, string $pathHeadScss = '', string $pathScss = ''): \DOMDocument {
        if('' !== $pathHeadScss) {
            $this->scssCompiler->setScssHeadFile($pathHeadScss);
        }

        if('' !== $pathScss) {
            $this->scssCompiler->setScssFile($pathScss);
        }

        $document = $this->addLayout($document);

        $document = $this->convert($document);
        $document = $this->configure($document);
        $this->inlineCss($document, $this->scssCompiler->getCss());
        $document = $this->cssCompile($document);
        return $document;
    }

    private function convert(\DOMDocument $document) {
        foreach($this->converters as $converter) {
            $document = $converter->convert($document);
        }
        return $document;
    }

    private function configure(\DOMDocument $document) {
        foreach($this->configurators as $configurator) {
            $document = $configurator->convert($document);
        }
        return $document;
    }

    private function cssCompile(\DOMDocument $document) {
        foreach($this->cssConverters as $converter) {
            $document = $converter->convert($document);
        }
        return $document;
    }

    private function addLayout(\DOMDocument $document) {
        $xPath = new \DOMXPath($document);
        if (count($xPath->query('//head')) === 0) {
            $document->loadHTML(mb_convert_encoding($this->getTwig()->render('layout.html', ['contents' => $document->saveHTML()]), 'HTML-ENTITIES', 'UTF-8'));
        }
        return $document;
    }

    private function inlineCss(\DOMDocument $document, string $css): void {
        $cssToInline = new CssToInlineStyles();
        $xPath = new \DOMXPath($document);
        $bodyNode = $xPath->query('//body')[0];
        $bodyHtml = $cssToInline->convert($document->saveHTML($bodyNode), $css);

        $doc2 = new \DOMDocument('1.0', 'UTF-8');
        $doc2->loadHTML(mb_convert_encoding($bodyHtml, 'HTML-ENTITIES', 'UTF-8'));
        $replacingNode = $doc2->getElementsByTagName('html')[0]->firstChild;

        $replace = $document->importNode($replacingNode, true);
        $bodyNode->parentNode->replaceChild($replace, $bodyNode);
    }

    private function HtmlPostProcessor($html) {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        $anchors = $xpath->query('//a');

        foreach ($anchors as $anchor) {
            $anchorIsEmpty = true;
            foreach ($anchor->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE) {
                    $anchorIsEmpty = false;
                    break;
                }
                if ($child->nodeType === XML_TEXT_NODE && trim($child->textContent) !== '') {
                    $anchorIsEmpty = false;
                    break;
                }
            }
            if (!$anchorIsEmpty) {
                continue;
            }

            $next = $anchor->nextSibling;
            while ($next && $next->nodeType === XML_TEXT_NODE && trim($next->textContent) === '') {
                $next = $next->nextSibling;
            }
            if (!$next) {
                continue;
            }
            $nextName = strtolower($next->nodeName);
            if ($nextName !== 'table' && $nextName !== 'img') {
                continue;
            }

            $nodesToMove = [];
            for ($node = $anchor->nextSibling; $node !== null; $node = $node->nextSibling) {
                $nodesToMove[] = $node;
                if (strtolower($node->nodeName) === 'img') {
                    break;
                }
            }
            foreach ($nodesToMove as $node) {
                $anchor->appendChild($node);
            }
        }

        return $doc->saveHTML($doc->getElementById('body'));
    }

    public function setTwig(Environment $twig) {
        $this->twig = $twig;
    }

    public function getTwig(): Environment {
        if(null !== $this->twig) {
            return $this->twig;
        }
        $loader = new FilesystemLoader(__DIR__.'/../resources');
        return new Environment($loader, ['autoescape' => false]);
    }
}
