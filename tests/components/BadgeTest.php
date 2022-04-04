<?php

namespace DemigodCode\BootstrapEmail\Converter;

use DemigodCode\BootstrapEmail\Compiler;
use DemigodCode\BootstrapEmail\ScssCompiler;
use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{

    protected $converter;

    protected function setUp(): void
    {
        $scss = new ScssCompiler();
        $this->converter = new Compiler($scss);
        parent::setUp();
    }

    public function testBadge()
    {
        $input = file_get_contents(__DIR__ . '/../resources/input/badge.html');
        $output = file_get_contents(__DIR__ . '/../resources/output/badge.html');
        $this->assertEquals($output, $this->converter->convertHtml($input));
    }

}