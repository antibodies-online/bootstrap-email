<?php

namespace AntibodiesOnline\BootstrapEmail;

use ScssPhp\ScssPhp\Compiler;

class ScssCompiler
{
    private $scssFile = 'bootstrap-email.scss';
    private $scssHeadFile = 'bootstrap-head.scss';
    private $cacheOptions;
    private $compiler;
    private $importPaths = [__DIR__ . '/../resources/'];

    public function __construct($cacheOptions = null)
    {
        if (is_array($cacheOptions) && isset($cacheOptions['cacheDir'])) {
            if (!is_dir($cacheOptions['cacheDir'])) {
                mkdir($cacheOptions['cacheDir'], 0775);
            }
        }
        $this->cacheOptions = $cacheOptions;

        $this->compiler = new Compiler($this->cacheOptions);
    }

    /**
     * Adds an import path. Add the lower priority first.
     */
    public function addImportPath(string $path): void
    {
        array_unshift($this->importPaths, $path);
    }

    /**
     * Sets the import paths. First element, highest priority.
     */
    public function setImportPath(array $paths): void
    {
        $this->importPaths = $paths;
    }

    public function setScssFile(string $scssFile): void
    {
        $this->scssFile = $scssFile;
    }

    public function getCss(): string {
        $this->compiler->setImportPaths($this->importPaths);
        return $this->compiler->compileString('@import "' . $this->scssFile . '";')->getCss();
    }

    public function setScssHeadFile(string $scssHeadFile): void
    {
        $this->scssHeadFile = $scssHeadFile;
    }

    public function getHeadCss(): string {
        $this->compiler->setImportPaths($this->importPaths);
        return $this->compiler->compileString('@import "' . $this->scssHeadFile . '";')->getCss();
    }

}