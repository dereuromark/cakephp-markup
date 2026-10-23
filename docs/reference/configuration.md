# Configuration

Each helper reads defaults from a matching application configuration namespace: `Markdown`, `Bbcode`, `Djot` or `Highlighter`. Put these entries in the array returned by `config/app.php` or `config/app_local.php`, or write them before the helper loads:

```php
Configure::write('Markdown', ['debug' => false, 'max_nesting_level' => 20]);
```

Import `Cake\Core\Configure` when using that form. Explicit helper load options override global defaults. Converter call options then override configured conversion defaults.

The `debug` option defaults to the application's debug setting. When enabled, helpers append conversion timing in an HTML comment. Set it to false for stable output in a beginner example or an exported fragment.

## Custom converters

Set `converter` to a class implementing the matching interface. For Markdown:

```php
$this->loadHelper('Markup.Markdown', [
    'converter' => \App\Markup\CustomMarkdown::class,
]);
```

A complete custom implementation:

```php
<?php
namespace App\Markup;

use Markup\Markdown\MarkdownInterface;

class CustomMarkdown implements MarkdownInterface {
    public function __construct(array $config = []) {
    }

    public function convert(string $text, array $options = []): string {
        return '<p>' . h($text) . '</p>';
    }
}
```

The other converter interfaces are `BbcodeInterface` and `DjotInterface` in their respective `Markup` namespaces. Highlighters use the `highlighter` option and implement `HighlighterInterface`.

A converter's constructor receives the helper configuration. Configuration is resolved when the helper or view loads; later global changes do not update existing instances. Conversion options can still change per call.
