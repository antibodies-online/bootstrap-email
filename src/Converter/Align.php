<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class Align extends AbstractConverter
{

    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " ax-left ")]' => 'left',
        '//*[contains(concat(" ", @class, " "), " ax-right ")]' => 'right',
        '//*[contains(concat(" ", @class, " "), " ax-center ")]' => 'center',
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'table-align.html';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        return 'ax-' . $identifier;
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string
    {
        if ($element->tagName !== 'table' && $element->tagName !== 'td') {
            $this->removeClass($element, 'ax-' . $identifier);
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

    protected function postDomUpdate(\DOMElement $element, string $identifier): void
    {
        $element->setAttribute('align', $identifier);
        parent::postDomUpdate($element, $identifier);
    }
}