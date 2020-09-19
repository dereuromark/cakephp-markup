<?php

namespace Markup\Markdown;

use Cake\Core\InstanceConfigTrait;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;

class CommonMarkMarkdown implements MarkdownInterface {

	use InstanceConfigTrait;

	/**
	 * @var \League\CommonMark\CommonMarkConverter|null
	 */
	protected $converter;

	/**
	 * @var array
	 */
	protected $_defaultConfig = [
	];

	/**
	 * @param array $config
	 */
	public function __construct(array $config = []) {
		$this->setConfig($config);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function _prepare($string) {
		return $string;
	}

	/**
	 * @param string $text
	 * @param array $options
	 *
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		$converter = $this->converter();

		$options += ['escape' => true];
		if ($options['escape']) {
			$text = (string)h($text);
		}

		return $converter->convertToHtml($text);
	}

	/**
	 * @return \League\CommonMark\CommonMarkConverter
	 */
	protected function converter(): CommonMarkConverter {
		if ($this->converter === null) {
			$this->converter = static::defaultConverter();
		}

		return $this->converter;
	}

	/**
	 * @return \League\CommonMark\CommonMarkConverter
	 */
	public static function defaultConverter(): CommonMarkConverter {
		$environment = Environment::createGFMEnvironment();

		return new CommonMarkConverter([], $environment);
	}

}
