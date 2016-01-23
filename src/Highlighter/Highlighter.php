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
	 * @param $string
	 * @return string
	 */
	protected function _prepare($string) {
		if ($this->_config['tabsToSpaces']) {
			$string = preg_replace('/\t/', str_repeat(' ', $this->_config['tabsToSpaces']), $string);
		}
		return $string;
	}

}
