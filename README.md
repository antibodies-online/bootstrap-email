# bootstrap-email
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/DemigodCode/bootstrap-email/master/LICENSE)
[![Packagist](https://img.shields.io/packagist/v/demigod-code/bootstrap-email.svg)](https://packagist.org/packages/demigod-code/bootstrap-email)
[![GitHub issues](https://img.shields.io/github/issues/DemigodCode/bootstrap-email.svg)](https://github.com/DemigodCode/bootstrap-email/issues)
[![PHP Composer Test](https://github.com/DemigodCode/bootstrap-email/actions/workflows/php.yml/badge.svg)](https://github.com/DemigodCode/bootstrap-email/actions/workflows/php.yml)

This is a port of [Bootstrap Email](https://github.com/bootstrap-email/bootstrap-email). Thanks to @stuyam!

## Installation

To install, add it to your `composer.json` file:

```json
{
  "require": {
      "DemigodCode/bootstrap-email": "master"
  }
}
```

or direct from [packagist](https://packagist.org/packages/DemigodCode/Bootstrap-Email)

    composer require "DemigodCode/bootstrap-email: master"

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Usage

You can use different methods to convert your boostrap-email html to an email client compatible html.

### Use DomDocument

```php
    $scss = new ScssCompiler();

    // Create a DOM Document
    $doc = new DOMDocument('1.0', 'UTF-8');
    \libxml_use_internal_errors(true);
    $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    \libxml_clear_errors();
    
    $converter = new Compiler($scss);
    $doc = $converter->convert($doc);
    $html = $doc->saveHTML();
```

### Use Html

```php
    $scss = new ScssCompiler();
    $converter = new Compiler($scss);
    $html = $converter->convertHtml(html);
```

### Use Custom Scss
```php
    $scss = new ScssCompiler();
    $scss->setScssFile(<PATH TO YOUR CUSTOM SCSS FILE);
    $scss->setScssHeadFile(<PATH TO YOUR CUSTOM HEAD SCSS FILE);
    $converter = new Compiler($scss);
    $html = $converter->convertHtml(html);
```

## Documentations
For full documentation, visit [bootstrapemail.com](https://bootstrapemail.com/docs/introduction)

## Community
For help, discussion about best practices, or any other conversation that would benefit from being searchable:

[Discuss Bootstrap Email on GitHub](https://github.com/bootstrap-email/bootstrap-email/discussions)

For PHP Port related questions, please open an issue.
