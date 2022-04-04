<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testContainerFluid()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/container-fluid.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/container-fluid.html');
        $this->assertEquals($output, $this->converter->convertHtml($input));
    }

    public function testContainer()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/container.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/container.html');
        $this->assertEquals($output, $this->converter->convertHtml($input));
    }

}