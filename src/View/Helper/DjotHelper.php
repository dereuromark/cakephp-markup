<?php

namespace Markup\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use InvalidArgumentException;
use Markup\Djot\DjotMarkup;

/**
 * DjotHelper for converting Djot markup to HTML in templates.
 *
 * Djot is a modern markup language created by John MacFarlane (author of CommonMark/Pandoc).
 *
 * @link https://djot.net/ Djot specification
 * @template TView of \Cake\View\View
 */
class DjotHelper extends Helper {

	/**
	 * @var \Markup\Djot\DjotInterface|null
	 */
	protected $_converter;

	/**
	 * Default configuration.
	 *
	 * - `converter`: The converter class to use. Defaults to DjotMarkup.
	 * - `debug`: Show conversion timing in HTML comments. Defaults to app debug setting.
	 * - `safeMode`: Enable XSS protection. Defaults to true.
	 * - `xhtml`: Output XHTML-compatible markup. Defaults to false.
	 * - `profile`: Profile name ('full', 'article', 'comment', 'minimal') or Profile instance.
	 *   Restricts which markup features are allowed. Defaults to null (all features).
	 *
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'converter' => DjotMarkup::class,
		'debug' => null,
		'safeMode' => true,
		'xhtml' => false,
		'profile' => null,
	];

	/**
	 * @var float
	 */
	protected $_time = 0.0;

	/**
	 * Constructor
	 *
	 * @param \Cake\View\View<TView> $View The View this helper is being attached to.
	 * @param array<string, mixed> $config Configuration settings for the helper.
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Djot');
		parent::__construct($View, $config + $defaults);

		if ($this->_config['debug'] === null) {
			$this->_config['debug'] = Configure::read('debug');
		}
	}

	/**
	 * Convert djot markup to HTML.
	 *
	 * Options:
	 * - `safeMode`: Enable XSS protection (blocks dangerous URLs, filters attributes). Defaults to true.
	 * - `xhtml`: Output XHTML-compatible markup. Defaults to false.
	 * - `strict`: Throw exceptions on parse errors. Defaults to false.
	 * - `profile`: Profile name or instance to restrict features ('comment', 'minimal', etc.).
	 *
	 * @param string $text Djot markup text.
	 * @param array<string, mixed> $options Conversion options.
	 * @return string Converted HTML.
	 */
	public function convert(string $text, array $options = []): string {
		if ($this->_config['debug']) {
			$this->_startTimer();
		}
		$html = $this->_getConverter()->convert($text, $options);
		if ($this->_config['debug']) {
			$html .= $this->_timeElapsedFormatted($this->_endTimer());
		}

		return trim($html);
	}

	/**
	 * @return \Markup\Djot\DjotInterface
	 */
	protected function _getConverter() {
		if ($this->_converter !== null) {
			return $this->_converter;
		}
		/** @var class-string<\Markup\Djot\DjotInterface> $className */
		$className = $this->_config['converter'];

		if (!class_exists($className)) {
			throw new InvalidArgumentException("Invalid converter class: {$className}");
		}

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
