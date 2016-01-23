<?php

namespace Markup\Highlighter;

class PhpHighlighter extends Highlighter {

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'templates' => [
			'code' => '<pre{{attr}}>{{content}}</pre>'
		]
	];

	/**
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function highlight($text, array $options = []) {
		$string = highlight_string($text, true);

		$options += $this->_config;

		$attr = ['class' => 'lang-' . $options['lang']];

		$options['attr'] = $this->templater()->formatAttributes($attr);
		$options['content'] = str_replace(["\r\n", "\n", "\r"], '', $string);

		return $this->templater()->format('code', $options);
	}

}
