<?php

namespace Markup\Djot;

interface DjotInterface {

	/**
	 * Convert djot markup to HTML.
	 *
	 * Options:
	 * - `safeMode`: Enable XSS protection - blocks dangerous URLs (javascript:, data:),
	 *   filters unsafe attributes (onclick, etc.), and escapes raw HTML. Defaults to true.
	 * - `xhtml`: Output XHTML-compatible markup (self-closing tags like <br />). Defaults to false.
	 * - `strict`: Throw exceptions on parse errors. Defaults to false.
	 * - `warnings`: Collect warnings during parsing. Defaults to false.
	 * - `profile`: Profile name ('full', 'article', 'comment', 'minimal') or Profile instance
	 *   to restrict which markup features are allowed. Defaults to null (all features).
	 *
	 * @param string $text
	 * @param array<string, mixed> $options
	 * @return string
	 */
	public function convert(string $text, array $options = []): string;

}
