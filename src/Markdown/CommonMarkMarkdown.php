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
	 * @param string $text
	 * @param array $options
	 *
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		$converter = $this->converter($options);

		return $converter->convertToHtml($text);
	}

	/**
	 * @param array $options
	 *
	 * @return \League\CommonMark\CommonMarkConverter
	 */
	protected function converter(array $options = []): CommonMarkConverter {
		if ($this->converter === null) {
			$this->converter = static::defaultConverter($options);
		}

		return $this->converter;
	}

	/**
	 * @param array $options
	 *
	 * @return \League\CommonMark\CommonMarkConverter
	 */
	public static function defaultConverter(array $options = []): CommonMarkConverter {
		$environment = Environment::createGFMEnvironment();

		$options += [
			'escape' => true,
		];

		if ($options['escape']) {
			$environment->mergeConfig([
				'html_input' => Environment::HTML_INPUT_ESCAPE,
			]);
		}

		return new CommonMarkConverter([], $environment);
	}

}
