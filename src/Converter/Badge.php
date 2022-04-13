<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Converter\AbstractConverter;

class Badge extends AbstractConverter
{

    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " badge ")]' => 'badge',
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'table-left.html';
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        $element->removeAttribute('class');
        return $element->ownerDocument->saveHTML($element);
    }
}