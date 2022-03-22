<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Padding extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " p-") or contains(concat(" ", @class, " "), " pt-") or contains(concat(" ", @class, " "), " pr-")'
        . ' or contains(concat(" ", @class, " "), " pb-") or contains(concat(" ", @class, " "), " pl-") or contains(concat(" ", @class, " "), " px-")'
        . ' or contains(concat(" ", @class, " "), " py-")]'=> 'padding',
    ];

    private $paddingRegex = '/(p[trblxy]?-(lg-)?\d+)/';

    protected function getTemplate(string $identifier): string
    {
        return 'table.html';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        $classes = [];
        preg_match_all($this->paddingRegex, parent::extractParentCssClasses($element, $identifier), $classes);
        return parent::extractParentCssClasses($element, $identifier) . ' w-full';
    }

    public function buildReplacementHtml(\DOMElement $element, string $identifier): string
    {
        return $element->ownerDocument->saveHTML($element);
    }

    protected function hasToBeSkipped(\DOMElement $element)
    {
        return in_array($element->tagName, ['table', 'td', 'a']);
    }

}