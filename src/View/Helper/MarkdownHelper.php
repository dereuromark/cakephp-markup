<?php

namespace Markup\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use Markup\Markdown\CommonMarkMarkdown;

class MarkdownHelper extends Helper {

	/**
	 * @var \Markup\Markdown\MarkdownInterface
	 */
	protected $_parser;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'converter' => CommonMarkMarkdown::class,
		'debug' => null, // Enable debug display
	];

	/**
	 * @var float
	 */
	protected $_time = 0.0;

	/**
	 * Constructor
	 *
	 * @param \Cake\View\View $View The View this helper is being attached to.
	 * @param array $config Configuration settings for the helper.
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Markdown');
		parent::__construct($View, $config + $defaults);

		if ($this->_config['debug'] === null) {
			$this->_config['debug'] = Configure::read('debug');
		}
	}

	/**
	 * Highlight a string.
	 *
	 * Options, depending on the specific highlighter class used:
	 * - templates
	 * - escape (defaults to true)
	 * - tabToSpaces (defaults to 4)
	 * - prefix (defaults to `language-`)
	 *
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		if ($this->_config['debug']) {
			$this->_startTimer();
		}
		$highlightedText = $this->_getConverter()->convert($text, $options);
		if ($this->_config['debug']) {
			$highlightedText .= $this->_timeElapsedFormatted($this->_endTimer());
		}

		return $highlightedText;
	}

	/**
	 * @return \Markup\Markdown\MarkdownInterface
	 */
	protected function _getConverter() {
		if (isset($this->_parser)) {
			return $this->_parser;
		}
		$className = $this->_config['parser'];

		$this->_parser = new $className($this->_config);

		return $this->_parser;
	}

	/**
	 * @return void
	 */
	protected function _startTimer() {
		$this->_time = microtime(true);
	}

	/**
	 * @return float
	 */
	protected function _endTimer() {
		$now = microtime(true);

		return $now - $this->_time;
	}

	/**
	 * @param float $time
	 * @return string
	 */
	protected function _timeElapsedFormatted($time) {
		return '<!-- ' . number_format($time * 1000, 3) . 'ms -->';
	}

}
