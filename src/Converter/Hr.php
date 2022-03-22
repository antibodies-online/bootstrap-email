<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Hr extends AbstractConverter
{
    protected $xPathsOptions = [
        '//hr' => 'hr'
    ];

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string {
        return $element->getAttribute('class'). ' hr my-5';
    }

    protected function getTemplate(string $identifier): string
    {
        return 'table.html';
    }
}