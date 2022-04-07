<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class BackgroundTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testTable()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/background.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/background.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}