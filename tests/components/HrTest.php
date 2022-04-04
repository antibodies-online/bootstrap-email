<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class HrTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testHr()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/hr.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/hr.html');
        $this->assertEquals($output, $this->converter->convertHtml($input));
    }

}