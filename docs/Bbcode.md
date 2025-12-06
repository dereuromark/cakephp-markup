# BBCode

Convert BBCode markup to HTML using [mjohnson/decoda](https://github.com/milesj/decoda).

## Installation

```bash
composer require mjohnson/decoda
```

## Basic Usage

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Bbcode', $optionalConfigArray);

// In our template file we can now convert some BBCode snippet
$string = <<<'TEXT'
Some [b]bold[/b] text and also some [i]italic[/i].
TEXT;

echo $this->Bbcode->convert($string);
```

## Configuration

Key options:
- `escape` (default: `true`): Escape HTML input for security

```php
$this->addHelper('Markup.Bbcode', [
    'escape' => true,
]);
```

## Write Your Own Converter

Implement the `BbcodeInterface` and configure it:

```php
// Configure
'Bbcode' => [
    'converter' => 'VendorName\PluginName\CustomBbcode',
],
```
