<?php

namespace Markup\Highlighter;

interface HighlighterInterface {

	/**
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function highlight($text, array $options = []);

}
