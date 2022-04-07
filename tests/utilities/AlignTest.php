<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class AlignTest extends TestCase
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
        $input = file_get_contents(__DIR__ . '/../resources/input/align.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/align.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}