# Documentation

## Highlighter

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Highlighter', $optionalConfigArray);

// In our template file we can now highlight some code snippet
$string = <<<'TEXT'
$result = 'string' . $this->request->query('key'); // Some comment
TEXT;

echo $this->Highlighter->highlight($string, ['lang' => 'php']);
```

### Supported Highlighters

#### PhpHighlighter
Using native PHP syntax highlighting this default highlighter does not need any dependencies.
Just add some basic CSS styling for all `<pre>` tags.

The output will be automatically escaped (safe) HTML code, e.g. for `php` language code:
```html
<pre class="lang-php"><code>
<span style="color: #000000">$key&nbsp;=&nbsp;'string'&nbsp;.&nbsp;$this-&gt;something-&gt;do(true);&nbsp;//&nbsp;Some&nbsp;comment</span>
</code></pre>
```

#### JsHighlighter
Using only JS via [highlightjs.org](https://highlightjs.org/) or [prismjs.com](http://prismjs.com/) this parser is lightweight on the server side.
It requires a CSS and JS file on top to do client-side highlighting "just in time".
```php
// Helper option
'highlighter' => 'Markup\Highlighter\JsHighlighter',
```

The output for `php` language code will be wrapped in
```html
<pre><code class="language-php">...</code></pre>
```
tags, for example.
Do not forget to add your custom code style CSS file and the JS code as documented at [highlightjs.org](https://highlightjs.org/usage/) or [prismjs.com](http://prismjs.com/#basic-usage).

### Write your own highlighter
You just have to implement the `HighlighterInterface` and ideally extend the abstract `Highlighter` class.
Then you can simply switch your code highlighting on demand or globally with Configure:
```php
// Configure
'Highlighter' => [
    'highlighter' => 'VendorName\PluginName\CustomHighlighter',
],
```

You should be able to easily use any custom highlighter this way.

If you are looking for a good auto-detection highlighter, take a look at [github.com/google/code-prettify](https://github.com/google/code-prettify).
In case you need the full options stack, it would be best to write a custom one here, otherwise a basic code template `<pre class="prettyprint">{{content}}</pre>` for `JsHighlighter` should do the trick.

### Additional Configuration
You can switch the template to use `<div>` instead of `<pre`> for example:
```php
// Helper option
'templates' => [
    'code' => '<div{{attr}}>{{content}}</div>',
],
```

## Markdown

For this you need to decide on a converter library.
By default, the included `CommonMarkMarkdown` class requires you to install its dependency:
```
composer require league/commonmark
```

Once you configured it the way you want it you can start using it.

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Markdown', $optionalConfigArray);

// In our template file we can now convert some markdown code snippet
$string = <<<'TEXT'
Some **bold** text and also some *italic*.
TEXT;

echo $this->Markdown->convert($string);
```


## BBCode

For this you need to decide on a converter library.
By default, the included `DecodaBbcode` class requires you to install its dependency:
```
composer require mjohnson/decoda
```

Once you configured it the way you want it you can start using it.

```php
// You must load the helper before in e.g. AppView
$this->addHelper('Markup.Bbcode', $optionalConfigArray);

// In our template file we can now convert some markdown code snippet
$string = <<<'TEXT'
Some [b]bold[/b] text and also some [i]italic[/i].
TEXT;

echo $this->Bbcode->convert($string);
```
