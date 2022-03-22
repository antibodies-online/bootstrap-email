<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Paragraph extends AbstractConverter
{
    protected $xPathsOptions = [
        '//p' => 'paragraph',
    ];

    protected function getTemplate(string $identifier): string
    {
        return '';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        return parent::extractParentCssClasses($element, $identifier) . ' mb-4';
    }

    public function buildReplacementHtml(\DOMElement $element, string $identifier): string
    {
        return $element->ownerDocument->saveHTML($element);
    }

    protected function hasToBeSkipped(\DOMElement $element)
    {
        return preg_match('/m[tyb]{1}-(lg-)?\d+/', $element->getAttribute('class'));
    }

}