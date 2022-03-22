<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Alert extends AbstractConverter
{

    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " alert ")]' => 'alert',
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'table.html';
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        $element->removeAttribute('class');
        return $element->ownerDocument->saveHTML($element);
    }
}