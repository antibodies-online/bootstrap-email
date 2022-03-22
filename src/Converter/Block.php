<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Block extends AbstractConverter
{

    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " to-table ")]' => 'to-table',
        '//block' => 'block',
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'table.html';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        if (strpos($element->getAttribute('class'), 'to-table') === false) {
            $element->setAttribute('class', $element->getAttribute('class') . ' to-table');
        }
        return parent::extractParentCssClasses($element, $identifier);
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        return $this->getInnerHtml($element);
    }

}