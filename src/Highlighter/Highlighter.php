<?php

namespace Markup\Highlighter;

use Cake\Core\InstanceConfigTrait;
use Cake\View\StringTemplateTrait;

abstract class Highlighter implements HighlighterInterface {

	use InstanceConfigTrait;

	use StringTemplateTrait;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [];

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$this->setConfig($config);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function _prepare($string) {
		if ($this->_config['tabToSpaces']) {
			$string = (string)preg_replace('/\t/', str_repeat(' ', $this->_config['tabToSpaces']), $string);
		}

		return $string;
	}

}
