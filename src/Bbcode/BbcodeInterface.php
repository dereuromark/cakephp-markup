<?php

namespace Markup\Bbcode;

interface BbcodeInterface {

	/**
	 * Convert BBCode markup to HTML.
	 *
	 * Options:
	 * - escape
	 *
	 * @param string $text
	 * @param array<string, mixed> $options
	 * @return string
	 */
	public function convert(string $text, array $options = []): string;

}
