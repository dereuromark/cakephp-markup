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
	 * @param array $options
	 * @return string
	 */
	public function convert(string $text, array $options = []): string;

}
