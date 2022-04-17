<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class Image
{

    protected $xPathsOptions = [
        '//img[contains(@style, " width: ") or contains(@style, " height: ")]' => 'img',
    ];

    public function convert(\DOMDocument $doc) {
        $xPath = new \DOMXPath($doc);

        foreach($this->xPathsOptions as $xPathSeletor => $identifier) {
            foreach(array_reverse(iterator_to_array($xPath->query($xPathSeletor))) as $element) {

                /** @var \DOMElement $element */
                preg_match('/; width: ?(.*?)(px)?;/', $element->getAttribute('style'), $width);
                if(isset($width[1])) {
                    if ($width[1] !== 'auto') {
                        $element->setAttribute('width', $width[1]);
                    }
                }

                preg_match('/; height: ?(.*?)(px)?;/', $element->getAttribute('style'), $height);
                if(isset($height[1])) {
                    if ($height[1] !== 'auto') {
                        $element->setAttribute('height', $height[1]);
                    }
                }
            }
        }
        return $doc;
    }
}
