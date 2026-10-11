# Code highlighting

The `Markup.Highlighter` helper defaults to native PHP highlighting and needs no extra PHP library.

Load it in `AppView::initialize()`:

```php
$this->loadHelper('Markup.Highlighter');
```

In a PHP template:

```php
<?= $this->Highlighter->highlight('<?php $answer = 42;', ['lang' => 'php']) ?>
```

Include the PHP opening tag so `highlight_string()` recognizes PHP syntax. `lang` sets the CSS class; it does not choose another native language parser. The default `div` wrapper surrounds the native highlighting output, which already includes a `pre` element.

## JavaScript highlighting

Select the JavaScript wrapper:

```php
$this->loadHelper('Markup.Highlighter', [
    'highlighter' => \Markup\Highlighter\JsHighlighter::class,
]);
```

It returns escaped code inside `pre` and `code` elements, with a `language-` class. Add the browser library and CSS yourself, following [Highlight.js](https://highlightjs.org/) or [Prism](https://prismjs.com/) instructions. The plugin does not load them or call their initialization functions.

```php
<?= $this->Highlighter->highlight('const answer = 42;', ['lang' => 'javascript', 'tabToSpaces' => 2]) ?>
```

`tabToSpaces` defaults to four for the JavaScript wrapper; zero leaves tabs intact. The `escape` option defaults to true. Disabling it allows raw HTML in the output.

Both highlighters support `prefix`, `lang` and `templates`. To change a wrapper:

```php
$this->loadHelper('Markup.Highlighter', [
    'highlighter' => \Markup\Highlighter\JsHighlighter::class,
    'templates' => ['code' => '<div{{attr}}>{{content}}</div>'],
]);
```

A custom implementation uses `Markup\Highlighter\HighlighterInterface`. The abstract `Highlighter` class supplies configuration and template support.
