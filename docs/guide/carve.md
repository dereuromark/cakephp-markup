# Carve

The `Markup.Carve` helper uses [markup-carve/carve-php](https://github.com/markup-carve/carve-php). See the [parent library](https://github.com/markup-carve/carve-php) and [Carve specification](https://markup-carve.github.io/carve/).

After [installation](./installation.md), load the helper in `AppView::initialize()`:

```php
$this->loadHelper('Markup.Carve');
```

In a PHP template:

```php
<?= $this->Carve->convert('Some *bold* text, /italic/ text and _underline_ text.') ?>
```

Carve's delimiters differ from Markdown and Djot. Asterisks make text bold, slashes make it italic, and underscores underline it.

## Output formats

`convert()` returns HTML. `text()` returns plain text, which still needs escaping when placed in an HTML template:

```php
<?= h($this->Carve->text('Some *bold* text.')) ?>
```

`markdown()` returns Markdown:

```php
<?php
$markdown = $this->Carve->markdown('Some /italic/ text.');
?>
<pre><?= h($markdown) ?></pre>
```

Outside the view layer, the corresponding methods are `CarveMarkup::convert()`, `toText()` and `toMarkdown()`.

## Options

The adapter supports `safeMode`, `xhtml`, `strict`, `warnings` and `profile`, with defaults matching the [Djot adapter](./djot.md). Use `full`, `article`, `comment`, `minimal`, a `MarkupCarve\Carve\Profile` instance, or null. Unknown names, including an empty string, throw.

The parent library strips dangerous URL schemes such as `javascript:`, `data:`, `vbscript:` and `file:` even with `safeMode` disabled. Keep safe mode enabled for untrusted input: it also escapes raw HTML and filters attributes.

Safe mode filters HTML output. It does not sanitize plain-text or Markdown output. Each output target has its own converter cache; changing call options rebuilds that target's converter.

To render `.crv` files directly, follow [Markup templates](./templates.md).
