<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testAlert()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/alert.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/alert.html');
        $this->assertEquals($output, $this->converter->convertHtml($input));
    }

}