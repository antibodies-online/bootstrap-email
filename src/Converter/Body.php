<?php

namespace DemigodCode\BootstrapEmail\Converter;

class Body extends AbstractConverter
{
    protected $xPathsOptions = [
        '//body' => 'body'
    ];

    protected function getTemplate(string $identifier): string
    {
        return 'body.html';
    }

    protected function extractParentCssClasses(\DOMElement $element, string $identifier): string
    {
        return parent::extractParentCssClasses($element, $identifier) . ' body';
    }

    protected function buildChildNodeContent(\DOMElement $element, string $identifier): string {
        return $this->getInnerHtml($element);
    }

    public function convert(\DOMDocument $doc)
    {
        $xPath = new \DOMXPath($doc);
        /** @var \DOMElement $previewNode */
        $bodyNode = $xPath->query('//body')[0];
        $bodyNode->setAttribute('class', $bodyNode->getAttribute('class') . ' body');

        $template = $this->twig->load('body.html');
        $html = $template->render(['classes' => $bodyNode->getAttribute('class'), 'contents' => $this->getInnerHtml($bodyNode)]);

        $doc2 = new \DOMDocument('1.0', 'UTF-8');
        $doc2->loadHTML(mb_convert_encoding('<html>'.$html.'</html>', 'HTML-ENTITIES', 'UTF-8'));
        $replacingNode = $doc2->getElementsByTagName('html')[0]->firstChild;

        $replace = $doc->importNode($replacingNode, true);
        $bodyNode->parentNode->replaceChild($replace, $bodyNode);
        return $doc;
    }
}