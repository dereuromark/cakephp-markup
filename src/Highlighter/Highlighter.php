<?php

namespace Markup\Highlighter;

use Cake\Core\InstanceConfigTrait;
use Cake\View\StringTemplateTrait;

abstract class Highlighter implements HighlighterInterface {

	use StringTemplateTrait;

	use InstanceConfigTrait;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
	];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		$this->config($config);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function _prepare($string) {
		if ($this->_config['tabToSpaces']) {
			$string = preg_replace('/\t/', str_repeat(' ', $this->_config['tabToSpaces']), $string);
		}
		return $string;
	}

}
