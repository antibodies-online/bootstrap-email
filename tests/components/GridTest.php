<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
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
        $input = file_get_contents(__DIR__ . '/../resources/input/grid.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/grid.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}