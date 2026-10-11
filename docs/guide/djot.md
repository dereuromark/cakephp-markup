# Djot

The `Markup.Djot` helper uses [php-collective/djot](https://github.com/php-collective/djot-php). See the [parent library docs](https://php-collective.github.io/djot-php/) and [Djot syntax](https://djot.net/).

After [installation](./installation.md), load the helper in `AppView::initialize()`:

```php
$this->loadHelper('Markup.Djot');
```

In a PHP template:

```php
<?= $this->Djot->convert('Some *bold* text and _italic_ text.') ?>
```

Djot uses single asterisks for bold and underscores for italic. Markdown uses different delimiters.

## Options and profiles

The adapter supports `safeMode`, `xhtml`, `strict`, `warnings` and `profile`. Defaults are `true`, `false`, `false`, `false` and `null`, respectively. Per-call options override configured defaults.

Supported profile names are `full`, `article`, `comment` and `minimal`. You can also supply a `Djot\Profile` instance. An unknown name throws instead of silently using unrestricted features.

- `full` allows all features.
- `article` removes raw HTML.
- `comment` limits structure, removes images and headings, and adds nofollow/ugc link attributes.
- `minimal` limits content to basic text formatting without links or images.

A null profile leaves features unrestricted. Profiles and safe mode serve different purposes: profiles choose features; safe mode filters HTML output. Keep safe mode enabled for untrusted content.

For `.djot` files, use the [markup-template guide](./templates.md). For PHP templates, the helper keeps your existing layout.
