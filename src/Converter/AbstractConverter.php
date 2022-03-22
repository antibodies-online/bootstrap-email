<?php

namespace DemigodCode\BootstrapEmail\Converter;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractConverter
{
    protected $xPathsOptions = [];
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__.'/../../resources/templates');
        $this->twig = new Environment($loader, ['autoescape' => false]);
    }

    public function convert(\DOMDocument $doc) {
        $xPath = new \DOMXPath($doc);

        foreach($this->xPathsOptions as $xPathSeletor => $identifier) {
            foreach(array_reverse(iterator_to_array($xPath->query($xPathSeletor))) as $element) {
                if(!$element instanceof \DOMElement)
                    continue;

                if($this->hasToBeSkipped($element)) {
                    continue;
                }

                $html = $this->buildReplacementHtml($element, $identifier);
                $doc2 = new \DOMDocument('1.0', 'UTF-8');
                $doc2->loadHTML(mb_convert_encoding('<html><div>'.$html.'<div></html>', 'HTML-ENTITIES', 'UTF-8'));

                $replacingNode = $doc2->getElementsByTagName('body')[0]->firstChild;
                $replace = $doc->importNode($replacingNode, true);
                $element->parentNode->replaceChild($replace, $element);
            }
        }
        return $doc;
    }

    public function buildReplacementHtml(\DOMElement $element, string $identifier): string {
        $template = $this->twig->load($this->getTemplate($identifier));
        return $template->render(['classes' => $this->extractParentCssClasses($element, $identifier), 'contents' => $this->buildChildNodeContent($element, $identifier)]);
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        return $this->getInnerHtml($element);
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string {
        return $element->getAttribute('class');
    }

    protected function removeClass(\DOMElement $element, string $class): \DOMElement {
        /** @var \DOMElement $element */
        $classes = explode(' ', $element->getAttribute('class'));
        if (($key = array_search($class, $classes)) !== false) {
            unset($classes[$key]);
        }

        if(count($classes) > 0) {
            $classes = implode(' ', $classes);
            $element->setAttribute('class', $classes);
        } else {
            $element->removeAttribute('class');
        }
        return $element;
    }

    protected function getInnerHtml(\DOMElement $element) {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    abstract protected function getTemplate(string $identifier): string;

    protected function hasToBeSkipped(\DOMElement $element)
    {
        return false;
    }
}