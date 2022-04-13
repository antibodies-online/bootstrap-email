<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class PreviewTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testPreview()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/preview.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/preview.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}