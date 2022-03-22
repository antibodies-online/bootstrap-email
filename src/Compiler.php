<?php

namespace DemigodCode\BootstrapEmail;

use DemigodCode\BootstrapEmail\Converter\Alert;
use DemigodCode\BootstrapEmail\Converter\Align;
use DemigodCode\BootstrapEmail\Converter\Badge;
use DemigodCode\BootstrapEmail\Converter\Block;
use DemigodCode\BootstrapEmail\Converter\Body;
use DemigodCode\BootstrapEmail\Converter\Button;
use DemigodCode\BootstrapEmail\Converter\Card;
use DemigodCode\BootstrapEmail\Converter\Container;
use DemigodCode\BootstrapEmail\Converter\Grid;
use DemigodCode\BootstrapEmail\Converter\HeadStyle;
use DemigodCode\BootstrapEmail\Converter\Hr;
use DemigodCode\BootstrapEmail\Converter\Image;
use DemigodCode\BootstrapEmail\Converter\Margin;
use DemigodCode\BootstrapEmail\Converter\MetaTag;
use DemigodCode\BootstrapEmail\Converter\Padding;
use DemigodCode\BootstrapEmail\Converter\PreviewText;
use DemigodCode\BootstrapEmail\Converter\Spacer;
use DemigodCode\BootstrapEmail\Converter\Spacing;
use DemigodCode\BootstrapEmail\Converter\Table;
use DemigodCode\BootstrapEmail\Converter\VersionComment;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Compiler
{
    private $converters = [];
    private $configurators = [];
    private $cssConverters = [];
    private $scssCompiler;

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

    public function convert(\DOMDocument $document): \DOMDocument {
        $document = $this->addLayout($document);

        $document = $this->compile($document);
        $document = $this->configure($document);
        $this->inlineCss($document, $this->scssCompiler->getCss());
        $document = $this->cssCompile($document);
        return $document;
    }

    private function compile(\DOMDocument $document) {
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
            $loader = new FilesystemLoader(__DIR__.'/../resources');
            $twig = new Environment($loader, ['autoescape' => false]);
            $template = $twig->load('layout.html');
            $document->loadHTML(mb_convert_encoding($template->render(['contents' => $document->saveHTML()]), 'HTML-ENTITIES', 'UTF-8'));
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

//        $cssToInlineStyles = new CssToInlineStyles($document->saveHTML(), $css);
//        $cssToInlineStyles->setUseInlineStylesBlock(true);
//        $cssToInlineStyles->setStripOriginalStyleTags(true);
//        $document->loadHTML($cssToInlineStyles->convert());
    }
}