<?php

namespace Markup\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use Markup\Bbcode\DecodaBbcode;

class BbcodeHelper extends Helper {

	/**
	 * @var \Markup\Bbcode\BbcodeInterface|null
	 */
	protected $_converter;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'converter' => DecodaBbcode::class,
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
	 * @param array<string, mixed> $config Configuration settings for the helper.
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Bbcode');
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
	 * @param array<string, mixed> $options
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		if ($this->_config['debug']) {
			$this->_startTimer();
		}
		$html = $this->_getConverter()->convert($text, $options);
		if ($this->_config['debug']) {
			$html .= $this->_timeElapsedFormatted($this->_endTimer());
		}

		return $html;
	}

	/**
	 * @return \Markup\Bbcode\BbcodeInterface
	 */
	protected function _getConverter() {
		if ($this->_converter !== null) {
			return $this->_converter;
		}
		/** @var class-string<\Markup\Bbcode\BbcodeInterface> $className */
		$className = $this->_config['converter'];

		$this->_converter = new $className($this->_config);

		return $this->_converter;
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
