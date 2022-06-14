<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testGrid()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/image.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/image.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}