<?php

namespace Markup\Highlighter;

class JsHighlighter extends Highlighter {

	/**
	 * @var array<string, mixed>
	 */
	protected $_defaultConfig = [
		'escape' => true,
		'tabToSpaces' => 4,
		'templates' => [
			'code' => '<pre><code{{attr}}>{{content}}</code></pre>',
		],
		'prefix' => 'language-',
		'lang' => 'txt',
	];

	/**
	 * Highlight code.
	 *
	 * Options:
	 * - lang
	 * - prefix
	 * - templates
	 * - escape
	 *
	 * @param string $text
	 * @param array<string, mixed> $options
	 * @return string
	 */
	public function highlight(string $text, array $options = []): string {
		$options += $this->_config;

		$text = $this->_prepare($text);

		if ($options['escape'] !== false) {
			$text = h($text);
		}

		$attr = ['class' => $options['prefix'] . $options['lang']];

		$options['attr'] = $this->templater()->formatAttributes($attr);
		$options['content'] = $text;

		return $this->templater()->format('code', $options);
	}

}
