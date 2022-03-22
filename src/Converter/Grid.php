<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Grid extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " col")]' => 'column',
        '//*[contains(concat(" ", @class, " "), " row ")]' => 'row'
    ];


    protected function getTemplate(string $identifier): string
    {
        if ($identifier === 'column') {
            return 'td.html';
        } elseif ($identifier === 'row') {
            return 'div.html';
        }
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        $xPath = new \DOMXPath($element->ownerDocument);
        if (count($xPath->query("//*[contains(@class, 'col-lg-')]", $element)) > 0) {
            $element->setAttribute('class', $element->getAttribute('class') . ' row-responsive');
        }
        return parent::extractParentCssClasses($element, $identifier);
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        if ($identifier == 'column') {
            return $this->getInnerHtml($element);
        } elseif ($identifier == 'row') {
            $template = $this->twig->load('table-to-tr.html');
            return $template->render(['classes' => '', 'contents' => $this->getInnerHtml($element)]);
        }
    }

}