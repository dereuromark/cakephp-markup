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
	 * Cached converter, keyed by the hash of options used to build it.
	 * Per-call options that differ from the cached key trigger a rebuild,
	 * preventing the unsafe variant from being served when callers ask for safe.
	 *
	 * @var \League\CommonMark\MarkdownConverterInterface|null
	 */
	protected ?MarkdownConverterInterface $converter = null;

	/**
	 * @var string|null
	 */
	protected ?string $converterKey = null;

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
	 * @param string $text
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function convert(string $text, array $options = []): string {
		$converter = $this->converter($options);

		return (string)$converter->convertToHtml($text);
	}

	/**
	 * Returns the converter instance, rebuilding it when the supplied options
	 * differ from those that produced the cached one. Without this check, the
	 * first call would pin safe/unsafe behavior for the lifetime of the
	 * instance — a real risk in long-lived FPM/queue workers.
	 *
	 * @param array<string, mixed> $options
	 *
	 * @return \League\CommonMark\MarkdownConverterInterface
	 */
	protected function converter(array $options = []): MarkdownConverterInterface {
		$key = md5(serialize($options));
		if ($this->converter === null || $this->converterKey !== $key) {
			$this->converter = static::defaultConverter($options);
			$this->converterKey = $key;
		}

		return $this->converter;
	}

	/**
	 * Builds a fresh CommonMark converter.
	 *
	 * Defaults are safe-by-default:
	 * - `escape` => true: maps to CommonMark's `html_input => 'escape'`, so raw
	 *   HTML in the source is escaped instead of rendered.
	 * - `allow_unsafe_links` => false: blocks dangerous URL schemes such as
	 *   `javascript:`, `data:`, `vbscript:` from passing through `[link](...)`
	 *   and `![image](...)`. CommonMark 1.x defaulted this to true, so the
	 *   override matters when composer resolves a 1.x line.
	 *
	 * @param array<string, mixed> $options
	 *
	 * @return \League\CommonMark\MarkdownConverterInterface
	 */
	public static function defaultConverter(array $options = []): MarkdownConverterInterface {
		$options += [
			'escape' => true,
			'allow_unsafe_links' => false,
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
