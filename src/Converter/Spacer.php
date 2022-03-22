<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Spacer extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " s-")]' => 'spacer',
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'table.html';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        return parent::extractParentCssClasses($element, $identifier) . ' w-full';
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string
    {
        return '&nbsp;';
    }

}