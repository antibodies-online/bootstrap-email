<?php

namespace AntibodiesOnline\BootstrapEmail\Converter;

use AntibodiesOnline\BootstrapEmail\Compiler;
use AntibodiesOnline\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testButton()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/button.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/button.html');
        $this->assertEquals($output, $this->converter->compileHtml($input));
    }

}