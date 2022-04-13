<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class Card extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " card ")]' => 'card',
        '//*[contains(concat(" ", @class, " "), " card-body ")]' => 'card-body'
    ];

    protected function getTemplate(string $classSelector): string
    {
        return 'table.html';
    }
}