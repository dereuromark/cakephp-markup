# Carve

Convert [Carve](https://github.com/markup-carve/carve) markup to HTML using [markup-carve/carve-php](https://github.com/markup-carve/carve-php).

Carve is a post-Markdown lightweight markup language with visual mnemonics and human-centered design.
The PHP implementation is a hard fork of [djot-php](https://github.com/php-collective/djot-php), so it shares
the same converter pipeline, profiles, and safe-mode semantics.

## Installation

```bash
composer require markup-carve/carve-php:dev-main
```

> [!NOTE]
> Carve-PHP currently ships only a `dev-main` branch (no tagged release yet), so it must be
> required with an explicit `dev-main` constraint.

## Basic Usage

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Carve', $optionalConfigArray);

// In our template file we can now convert some Carve markup
$string = <<<'TEXT'
# Heading

Some *bold* text and also some /italic/.

A [link](https://example.com) and `inline code`.
TEXT;

echo $this->Carve->convert($string);
```

Carve uses visual mnemonics for inline formatting: `*bold*` for strong, `/italic/` for emphasis
(the slashes lean like italic text), and `_underline_` for underline.

## Configuration Options

| Option | Default | Description |
|--------|---------|-------------|
| `safeMode` | `true` | Enable XSS protection - blocks dangerous URLs (javascript:, data:), filters unsafe attributes (onclick, etc.), and escapes raw HTML |
| `xhtml` | `false` | Output XHTML-compatible markup (self-closing tags like `<br />`) |
| `strict` | `false` | Throw exceptions on parse errors instead of silently handling them |
| `profile` | `null` | Restrict which markup features are allowed |

> [!NOTE]
> Dangerous URL schemes (`javascript:`, `data:`, `vbscript:`, `file:`) are stripped by the
> library's always-on baseline hardening, regardless of `safeMode`. Safe mode adds raw-HTML
> escaping and attribute filtering on top.

### Profiles

Profiles let you restrict which Carve features are available, useful for user-generated content:

| Profile | Use Case | Allows |
|---------|----------|--------|
| `'full'` | Trusted content | All features |
| `'article'` | Blog posts | All formatting, no raw HTML |
| `'comment'` | User comments | Basic formatting, nofollow links, no images/headings |
| `'minimal'` | Chat/micro-posts | Text formatting only, no links/images |

Example:
```php
// Restrict features for user-generated content
$this->addHelper('Markup.Carve', [
    'profile' => 'comment',
]);
```

You can also create custom profiles using the `Carve\Profile` class.

## CarveView - Render .carve Templates

You can render `.carve` files directly as templates:

```php
// In your controller
public function documentation(): void
{
    $this->viewBuilder()->setClassName('Markup.Carve');
}
```

Create templates with `.carve` extension (e.g., `templates/Pages/documentation.carve`):

```carve
# Welcome, {{username}}!

This page was rendered from a `.carve` template file.

You can use all Carve features:

- *Bold* and /italic/ text
- [Links](https://example.com)
- `Code blocks`
```

View variables are available for substitution using `{{varName}}` syntax.

## Write Your Own Converter

Implement the `CarveInterface` and configure it:

```php
// Configure
'Carve' => [
    'converter' => 'VendorName\PluginName\CustomCarve',
],
```

This allows full customization of the Carve conversion process.
