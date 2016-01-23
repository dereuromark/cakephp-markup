# Markup Plugin for CakePHP
[![Build Status](https://api.travis-ci.org/dereuromark/cakephp-markup.svg)](https://travis-ci.org/dereuromark/cakephp-markup)
[![Coverage Status](https://coveralls.io/repos/dereuromark/cakephp-markup/badge.svg)](https://coveralls.io/r/dereuromark/cakephp-markup)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/cakephp-markup/license)](https://packagist.org/packages/dereuromark/cakephp-markup)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-markup/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-markup)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

A CakePHP 3.x plugin to
- easily use code syntax highlighters.

## Setup
```
composer require dereuromark/cakephp-markup
```
and
```
bin/cake plugin load Markup
```

## Demo
http://sandbox3.dereuromark.de/sandbox/examples/markup

## Usage

```php
// You must load the helper before
$this->loadHelper('Markup.Highlighter', $optionalConfigArray);

// In our ctp file we can now highlight some code snippet
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
Using only JS via highlightjs.org this parser is lightweight on the server side.
It requires a CSS and JS file on top to do client-side highlighting "just in time".
```php
// Helper option
'highlighter' => 'Markup\Highlighter\JsHighlighter'
```

The output for `php` language code will be wrapped in
```html
<pre><code class="lang-php">...</code></pre>
```
tags, for example.

### Write your own highlighter
You just have to implement the `HighlighterInterface` and ideally extend the abstract `Highlighter` class.
Then you can simply switch your code highlighting on demand or globally with Configure:
```php
	// Configure
	'Highlighter' => [
		'highlighter' => 'VendorName\PluginName\CustomHighlighter'
	]
```

### Additional Configuration
You can switch the template to use `<div>` instead of `<pre`> for example:
```php
// Helper option
'templates' => [
	'code' => '<div{{attr}}>{{content}}</div>'
]
```

## TODO
- Add more highlighters (you can also just link your own here)
- Add markup parsers and possibly View classes (BBCode, Markdown, ...)

## License
MIT
