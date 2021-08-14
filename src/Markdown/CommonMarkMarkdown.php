<?php

namespace Markup\Markdown;

use Cake\Core\InstanceConfigTrait;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment as LegacyEnvironment;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\MarkdownConverterInterface;

class CommonMarkMarkdown implements MarkdownInterface {

	use InstanceConfigTrait;

	/**
	 * @var \League\CommonMark\MarkdownConverterInterface|null
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

		return (string)$converter->convertToHtml($text);
	}

	/**
	 * @param array $options
	 *
	 * @return \League\CommonMark\MarkdownConverterInterface
	 */
	protected function converter(array $options = []): MarkdownConverterInterface {
		if ($this->converter === null) {
			$this->converter = static::defaultConverter($options);
		}

		return $this->converter;
	}

	/**
	 * @param array $options
	 *
	 * @return \League\CommonMark\MarkdownConverterInterface
	 */
	public static function defaultConverter(array $options = []): MarkdownConverterInterface {

		$options += [
			'escape' => true,
		];
		if ($options['escape']) {
			$options['html_input'] = 'escape';
		}
		unset($options['escape']);

		if (!class_exists(Environment::class)) {
			$environment = LegacyEnvironment::createGFMEnvironment();
			$environment->mergeConfig($options);
		} else {
			$environment = new Environment($options);
			$environment->addExtension(new CommonMarkCoreExtension());
			$environment->addExtension(new GithubFlavoredMarkdownExtension());
		}

		if (!class_exists(MarkdownConverter::class)) {
			return new CommonMarkConverter([], $environment);
		}

		return new MarkdownConverter($environment);
	}

}
