# Djot

Convert [Djot](https://djot.net/) markup to HTML using [php-collective/djot](https://github.com/php-collective/djot-php).

Djot is a modern markup language created by John MacFarlane (author of CommonMark/Pandoc).
It provides a cleaner syntax than Markdown with more consistent behavior.

## Installation

```bash
composer require php-collective/djot
```

## Basic Usage

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Djot', $optionalConfigArray);

// In our template file we can now convert some djot markup
$string = <<<'TEXT'
# Heading

Some *bold* text and also some _italic_.

A [link](https://example.com) and `inline code`.
TEXT;

echo $this->Djot->convert($string);
```

## Configuration Options

| Option | Default | Description |
|--------|---------|-------------|
| `safeMode` | `true` | Enable XSS protection - blocks dangerous URLs (javascript:, data:), filters unsafe attributes (onclick, etc.), and escapes raw HTML |
| `xhtml` | `false` | Output XHTML-compatible markup (self-closing tags like `<br />`) |
| `strict` | `false` | Throw exceptions on parse errors instead of silently handling them |
| `profile` | `null` | Restrict which markup features are allowed |

### Profiles

Profiles let you restrict which Djot features are available, useful for user-generated content:

| Profile | Use Case | Allows |
|---------|----------|--------|
| `'full'` | Trusted content | All features |
| `'article'` | Blog posts | All formatting, no raw HTML |
| `'comment'` | User comments | Basic formatting, nofollow links, no images/headings |
| `'minimal'` | Chat/micro-posts | Text formatting only, no links/images |

Example:
```php
// Restrict features for user-generated content
$this->addHelper('Markup.Djot', [
    'profile' => 'comment',
]);
```

You can also create custom profiles using the `Djot\Profile` class.

## DjotView - Render .djot Templates

You can render `.djot` files directly as templates:

```php
// In your controller
public function documentation(): void
{
    $this->viewBuilder()->setClassName('Markup.Djot');
}
```

Create templates with `.djot` extension (e.g., `templates/Pages/documentation.djot`):

```djot
# Welcome, {{username}}!

This page was rendered from a `.djot` template file.

You can use all djot features:

- *Bold* and _italic_ text
- [Links](https://example.com)
- `Code blocks`
```

View variables are available for substitution using `{{varName}}` syntax.

## Write Your Own Converter

Implement the `DjotInterface` and configure it:

```php
// Configure
'Djot' => [
    'converter' => 'VendorName\PluginName\CustomDjot',
],
```

This allows full customization of the Jdot conversion process.
See the cookbook in the Jdot library.
