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
		'lang' => 'text',
		'templates' => [
			'code' => '<pre{{attr}}>{{content}}</pre>'
		]
	];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		$this->config($config);
	}

}
