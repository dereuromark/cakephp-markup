<?php

namespace Markup\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;

class HighlighterHelper extends Helper {

	/**
	 * @var \Markup\Highlighter\HighlighterInterface
	 */
	protected $highlighter;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
		'autoParse' => false,
		'debug' => null, // Enable caching mode
	];

	/**
	 * Constructor
	 *
	 * @param \Cake\View\View $View The View this helper is being attached to.
	 * @param array $config Configuration settings for the helper.
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Highlighter');
		parent::__construct($View, $config + $defaults);

		if ($this->_config['debug'] === null) {
			$this->_config['debug'] = Configure::read('debug');
		}
	}

	/**
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public function highlight($text, array $options = []) {
		return $this->_getHighlighter()->highlight();
	}

	/**
	 * @return \Markup\Highlighter\HighlighterInterface
	 */
	protected function _getHighlighter() {
		if (isset($this->highlighter)) {
			return $this->highlighter;
		}
		$this->highlighter = new Highlighter($this->_config);

		return $this->highlighter;
	}

}
