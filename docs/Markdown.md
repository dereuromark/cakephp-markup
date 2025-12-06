# Markdown

Convert Markdown markup to HTML using [league/commonmark](https://commonmark.thephpleague.com/).

## Installation

```bash
composer require league/commonmark
```

## Basic Usage

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Markdown', $optionalConfigArray);

// In our template file we can now convert some markdown code snippet
$string = <<<'TEXT'
Some **bold** text and also some *italic*.
TEXT;

echo $this->Markdown->convert($string);
```

## Configuration

The helper supports all [league/commonmark configuration options](https://commonmark.thephpleague.com/2.0/configuration/).

Key options:
- `escape` (default: `true`): Escape HTML input for security

```php
$this->addHelper('Markup.Markdown', [
    'escape' => true,
]);
```

## Write Your Own Converter

Implement the `MarkdownInterface` and configure it:

```php
// Configure
'Markdown' => [
    'converter' => 'VendorName\PluginName\CustomMarkdown',
],
```
