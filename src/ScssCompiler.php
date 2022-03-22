<?php

namespace DemigodCode\BootstrapEmail;

use ScssPhp\ScssPhp\Compiler;

class ScssCompiler
{
    private $scssFile = 'bootstrap-email.scss';
    private $scssHeadFile = 'bootstrap-head.scss';

    public function setScssFile(string $scssFile): void
    {
        $this->scssFile = $scssFile;
    }

    public function getCss(): string {
        $compiler = new Compiler();
        $compiler->addImportPath(__DIR__ . '/../resources/');
        return $compiler->compileString('@import "' . $this->scssFile . '";')->getCss();
    }

    public function setScssHeadFile(string $scssHeadFile): void
    {
        $this->scssHeadFile = $scssHeadFile;
    }

    public function getHeadCss(): string {
        $compiler = new Compiler();
        $compiler->addImportPath(__DIR__ . '/../resources/');
        return $compiler->compileString('@import "' . $this->scssHeadFile . '";')->getCss();
    }

}