<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Button extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " btn ")]' => 'btn'
    ];

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        $element->removeAttribute('class');
        return $element->ownerDocument->saveHTML($element);
    }

    protected function getTemplate(string $classSelector): string
    {
        return 'table.html';
    }
}