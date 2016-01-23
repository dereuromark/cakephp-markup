<?php

namespace Markup\Highlighter;

class JsHighlighter extends Highlighter {

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'escape' => true,
		'tabsToSpaces' => 4,
		'templates' => [
			'code' => '<pre><code{{attr}}>{{content}}</code></pre>'
		]
	];

	/**
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function highlight($text, array $options = []) {
		$options += $this->_config;

		$text = $this->_prepare($text);

		if ($options['escape'] !== false) {
			$text = h($text);
		}

		$attr = ['class' => 'lang-' . $options['lang']];

		$options['attr'] = $this->templater()->formatAttributes($attr);
		$options['content'] = $text;

		return $this->templater()->format('code', $options);
	}

}
