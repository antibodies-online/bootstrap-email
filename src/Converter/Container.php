<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

class Container extends AbstractConverter
{
    protected $xPathsOptions = [
        '//*[contains(concat(" ", @class, " "), " container ")]' => 'container',
        '//*[contains(concat(" ", @class, " "), " container-fluid ")]' => 'container-fluid'
    ];

    protected function getTemplate(string $identifier): string
    {
        if($identifier === 'container-fluid') {
            return 'table.html';
        }
        return 'container.html';
    }
}