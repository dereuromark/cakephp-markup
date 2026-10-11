# BBCode

The `Markup.Bbcode` helper uses [mjohnson/decoda](https://github.com/milesj/decoda). Refer to the [parent library](https://github.com/milesj/decoda) for its tag syntax.

After [installation](./installation.md), load the helper in `AppView::initialize()`:

```php
$this->loadHelper('Markup.Bbcode');
```

In a PHP template:

```php
<?= $this->Bbcode->convert('Some [b]bold[/b] text and [i]italic[/i] text.') ?>
```

The built-in adapter enables Decoda's default, email, image, URL, text, block, code, list, table, video and quote filters, plus its censor and clickable hooks. Its conversion option is `escape`, which defaults to true and maps to Decoda's `escapeHtml` option.

```php
$this->loadHelper('Markup.Bbcode', ['escape' => true]);
```

Per-call options override configured defaults. `escape: false` allows raw HTML and should be used only with trusted content.

Installing media-embed selects the plugin's media-embed video filter. Without it, the standard Decoda filter is used. A custom filter set requires a custom converter implementing `Markup\Bbcode\BbcodeInterface`; see [Configuration](../reference/configuration.md).
