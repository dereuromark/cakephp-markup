<?php

namespace Markup\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;
use InvalidArgumentException;
use Markup\Markdown\CommonMarkMarkdown;

/**
 * MarkdownHelper - Converts Markdown text to HTML.
 *
 * Provides a simple interface for converting Markdown-formatted text to HTML
 * using a configurable converter (CommonMark by default).
 *
 * Usage:
 * ```php
 * // In your template
 * echo $this->Markdown->convert($markdownText);
 * ```
 *
 * @author Mark Scherer
 * @license MIT
 */
class MarkdownHelper extends Helper {

	/**
	 * @var \Markup\Markdown\MarkdownInterface|null
	 */
	protected $_converter;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
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
	 * @param array<string, mixed> $config Configuration settings for the helper.
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Markdown');
		parent::__construct($View, $config + $defaults);

		if ($this->_config['debug'] === null) {
			$this->_config['debug'] = Configure::read('debug');
		}
	}

	/**
	 * Convert Markdown text to HTML.
	 *
	 * @param string $text Markdown-formatted text to convert.
	 * @param array<string, mixed> $options Options passed to the converter.
	 *
	 * @return string Converted HTML output.
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
	 * @return \Markup\Markdown\MarkdownInterface
	 */
	protected function _getConverter() {
		if ($this->_converter !== null) {
			return $this->_converter;
		}
		/** @var class-string<\Markup\Markdown\MarkdownInterface> $className */
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
