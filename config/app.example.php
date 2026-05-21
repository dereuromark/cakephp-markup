<?php

/**
 * Markup Example Configuration
 *
 * Merge the keys below into your application's config/app.php (or
 * config/app_local.php) — do not replace the whole file, since this snippet
 * only contains this plugin's configuration. When copying entries that
 * reference imported classes, use fully-qualified class names or move the
 * `use` imports to the top of the target file. Customize the values as needed.
 *
 * Each markup helper reads its own top-level namespace via Configure and merges it as
 * defaults at construction time (helper options passed at load time still win). The
 * `debug` option defaults to null, which means it falls back to the app `debug` setting;
 * when truthy, the helper appends conversion timing to the output.
 */
return [
	// Read by Markup\View\Helper\BbcodeHelper.
	'Bbcode' => [
		// Converter class implementing Markup\Bbcode\BbcodeInterface.
		// Default: Markup\Bbcode\DecodaBbcode.
		'converter' => \Markup\Bbcode\DecodaBbcode::class,
		// Append timing info to output. null = follow app `debug`. Default: null.
		'debug' => null,
	],

	// Read by Markup\View\Helper\HighlighterHelper.
	'Highlighter' => [
		// Highlighter class implementing Markup\Highlighter\HighlighterInterface.
		// Default: Markup\Highlighter\PhpHighlighter.
		'highlighter' => \Markup\Highlighter\PhpHighlighter::class,
		// Append timing info to output. null = follow app `debug`. Default: null.
		'debug' => null,
	],

	// Read by Markup\View\Helper\DjotHelper.
	'Djot' => [
		// Converter class implementing Markup\Djot\DjotInterface.
		// Default: Markup\Djot\DjotMarkup.
		'converter' => \Markup\Djot\DjotMarkup::class,
		// Append timing info to output. null = follow app `debug`. Default: null.
		'debug' => null,
		// Enable XSS protection (blocks dangerous URLs, filters attributes). Default: true.
		'safeMode' => true,
		// Output XHTML-compatible markup. Default: false.
		'xhtml' => false,
		// Profile name ('full', 'article', 'comment', 'minimal') or Profile instance that
		// restricts which markup features are allowed. null = all features. Default: null.
		'profile' => null,
	],

	// Read by Markup\View\Helper\MarkdownHelper.
	'Markdown' => [
		// Converter class implementing Markup\Markdown\MarkdownInterface.
		// Default: Markup\Markdown\CommonMarkMarkdown.
		'converter' => \Markup\Markdown\CommonMarkMarkdown::class,
		// Append timing info to output. null = follow app `debug`. Default: null.
		'debug' => null,
	],
];
