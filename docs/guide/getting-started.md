# Getting started

Complete [installation](./installation.md), including the Markdown library. This example uses a normal PHP template and your existing application layout.

In `src/View/AppView.php`, load the helper and keep your existing initialization code:

```php
public function initialize(): void {
    parent::initialize();

    $this->loadHelper('Markup.Markdown', ['debug' => false]);
}
```

In any existing PHP template, such as `templates/Pages/home.php`:

```php
<?php
$source = '# Welcome' . "\n\n" . 'Some **bold** text and *italic* text.';
?>
<div class="article"><?= $this->Markdown->convert($source) ?></div>
```

Load that page. You should see a Welcome heading, bold text and italic text. The converter escapes raw HTML and blocks unsafe link schemes by default, so do not wrap its HTML output in `h()`.

Conversion options can be supplied for a single call:

```php
<?= $this->Markdown->convert($source, ['max_nesting_level' => 20]) ?>
```

For options shared by every conversion, see [Configuration](../reference/configuration.md). To use another format, load its matching helper and follow the [Markdown](./markdown.md), [BBCode](./bbcode.md), [Djot](./djot.md) or [Carve](./carve.md) guide. Their delimiters differ.
