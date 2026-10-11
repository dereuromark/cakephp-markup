# Markdown

The `Markup.Markdown` helper uses [league/commonmark](https://commonmark.thephpleague.com/). Read its [syntax and extensions](https://commonmark.thephpleague.com/2.x/) and [configuration reference](https://commonmark.thephpleague.com/2.x/configuration/) for advanced conversion options.

After [installation](./installation.md), load the helper in `AppView::initialize()`:

```php
$this->loadHelper('Markup.Markdown');
```

In a PHP template:

```php
<?= $this->Markdown->convert('Some **bold** text and *italic* text.') ?>
```

The built-in converter enables the CommonMark core and GitHub Flavored Markdown extensions, including tables, task lists and strikethrough. It uses `escape: true` and `allow_unsafe_links: false` by default.

## Conversion options

Helper defaults and per-call options are passed to the converter. Per-call values win:

```php
$this->loadHelper('Markup.Markdown', ['max_nesting_level' => 20]);
```

The plugin's `escape` option controls raw HTML handling. With `escape: true`, it forces CommonMark's `html_input` to `escape`. To use another CommonMark HTML policy, disable that override and specify the policy:

```php
<?= $this->Markdown->convert($source, ['escape' => false, 'html_input' => 'strip']) ?>
```

Use raw HTML only for content you trust. Link filtering is a separate option and remains enabled unless you set `allow_unsafe_links` to true.

For code outside the view layer:

```php
$converter = new \Markup\Markdown\CommonMarkMarkdown(['max_nesting_level' => 20]);
$html = $converter->convert('Some **bold** text.');
```

A custom converter implements `Markup\Markdown\MarkdownInterface`; see [Configuration](../reference/configuration.md).
