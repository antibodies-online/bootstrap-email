<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Margin extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " my-") or contains(concat(" ", @class, " "), " mt-") or contains(concat(" ", @class, " "), " mb-")]' => 'margin',
    ];

    protected function getTemplate(string $identifier): string
    {
        return '';
    }

    public function buildReplacementHtml(\DOMElement $element, string $identifier): string {
        preg_match('/m[ty]{1}-(lg-)?(\d+)/', $element->getAttribute('class'), $needTopClass);
        preg_match('/m[by]{1}-(lg-)?(\d+)/', $element->getAttribute('class'), $needBottomClass);
        $element->setAttribute('class', preg_replace('/(m[tby]{1}-(lg-)?\d+)/', '', $element->getAttribute('class')));

        $html = '';
        if(count($needTopClass) > 0) {
            $template = $this->twig->load('div.html');
            unset($needTopClass[0]);
            $html .= $template->render(['classes' => 's-'.implode('', $needTopClass), 'contents' => '']);
        }
        $html .= $element->ownerDocument->saveHTML($element);
        if(count($needBottomClass) > 0) {
            $template = $this->twig->load('div.html');
            unset($needBottomClass[0]);
            $html .= $template->render(['classes' => 's-'.implode('', $needBottomClass), 'contents' => '']);
        }

        return $html;
    }
}