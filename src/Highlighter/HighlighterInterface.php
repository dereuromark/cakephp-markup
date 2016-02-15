<?php

namespace Markup\Highlighter;

interface HighlighterInterface {

	/**
	 * Highlight code.
	 *
	 * Options:
	 * - lang
	 * - prefix
	 * - templates
	 *
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function highlight($text, array $options = []);

}
