<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
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
        $input = file_get_contents(__DIR__ . '/../resources/input/table.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/table.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}