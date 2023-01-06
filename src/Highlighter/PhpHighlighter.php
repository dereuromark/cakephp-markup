<?php

namespace Markup\Highlighter;

class PhpHighlighter extends Highlighter {

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'templates' => [
			'code' => '<pre{{attr}}>{{content}}</pre>',
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
	 *
	 * @param string $text
	 * @param array<string, mixed> $options
	 * @return string
	 */
	public function highlight(string $text, array $options = []): string {
		$string = highlight_string($text, true);

		$options += $this->_config;

		$attr = ['class' => $options['prefix'] . $options['lang']];

		$options['attr'] = $this->templater()->formatAttributes($attr);
		$options['content'] = str_replace(["\r\n", "\n", "\r"], '', $string);

		return $this->templater()->format('code', $options);
	}

}
