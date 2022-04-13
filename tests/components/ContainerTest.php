<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
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
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

    public function testContainer()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/container.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/container.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}