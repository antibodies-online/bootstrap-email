<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class Padding extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " p-") or contains(concat(" ", @class, " "), " pt-") or contains(concat(" ", @class, " "), " pr-")'
        . ' or contains(concat(" ", @class, " "), " pb-") or contains(concat(" ", @class, " "), " pl-") or contains(concat(" ", @class, " "), " px-")'
        . ' or contains(concat(" ", @class, " "), " py-")]' => 'padding',
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
        return implode(' ', $classes[0]);
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string
    {
        if ($element->tagName !== 'table' && $element->tagName !== 'td') {
            $classes = [];
            preg_match_all($this->paddingRegex, $element->getAttribute('class'), $classes);
            foreach ($classes as $class) {
                if (!empty($class[0]))
                    $this->removeClass($element, $class[0]);
            }
        }
        return $element->ownerDocument->saveHTML($element);
    }

    public function buildReplacementHtml(\DOMElement $element, string $identifier): string
    {
        if ($element->tagName !== 'table' && $element->tagName !== 'td') {
            return parent::buildReplacementHtml($element, $identifier);
        }
        return $element->ownerDocument->saveHTML($element);
    }

    protected function hasToBeSkipped(\DOMElement $element)
    {
        return in_array($element->tagName, ['table', 'td', 'a']);
    }
}