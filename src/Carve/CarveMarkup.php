<?php

namespace Markup\Carve;

use Cake\Core\InstanceConfigTrait;
use MarkupCarve\Carve\CarveConverter;
use MarkupCarve\Carve\Parser\BlockParser;
use MarkupCarve\Carve\Profile;

/**
 * Carve markup converter using markup-carve/carve-php.
 *
 * Carve is a post-Markdown lightweight markup language with visual mnemonics and
 * human-centered design. The PHP implementation is a hard fork of djot-php, so it
 * shares the same converter pipeline, profiles, and safe-mode semantics.
 *
 * @link https://github.com/markup-carve/carve Carve specification
 * @link https://github.com/markup-carve/carve-php PHP implementation
 */
class CarveMarkup implements CarveInterface {

	use InstanceConfigTrait;

	/**
	 * One cached converter per render target, plus the options hash it was
	 * built from. Per-call options that differ from the cached hash rebuild
	 * that target's converter, preventing a `safeMode=false` instance from
	 * serving a later call that requested `safeMode=true` - a real risk in
	 * long-lived FPM/queue workers.
	 *
	 * Holding one converter per target rather than one per option set keeps
	 * the cache at three entries in a worker that renders with many different
	 * option combinations.
	 *
	 * @var array<string, \MarkupCarve\Carve\CarveConverter>
	 */
	protected array $converters = [];

	/**
	 * Options hash the cached converter of each target was built from.
	 *
	 * @var array<string, string>
	 */
	protected array $converterKeys = [];

	/**
	 * Default configuration.
	 *
	 * - `safeMode`: Enable XSS protection - blocks dangerous URLs (javascript:, data:),
	 *   filters unsafe attributes (onclick, etc.), and escapes raw HTML. Defaults to true.
	 * - `xhtml`: Output XHTML-compatible markup (self-closing tags like <br />). Defaults to false.
	 * - `strict`: Throw exceptions on parse errors instead of silently handling them. Defaults to false.
	 * - `warnings`: Collect warnings during parsing (accessible via converter). Defaults to false.
	 * - `profile`: A Profile instance or profile name ('full', 'article', 'comment', 'minimal')
	 *   to restrict which markup features are allowed. Defaults to null (all features allowed).
	 *
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'safeMode' => true,
		'xhtml' => false,
		'strict' => false,
		'warnings' => false,
		'profile' => null,
	];

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
		$options += $this->getConfig();

		$converter = $this->converter('html', $options);

		return $converter->convert($text);
	}

	/**
	 * @param string $text
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function toText(string $text, array $options = []): string {
		$options += $this->getConfig();

		$converter = $this->converter('text', $options);

		return $converter->convert($text);
	}

	/**
	 * @param string $text
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function toMarkdown(string $text, array $options = []): string {
		$options += $this->getConfig();

		$converter = $this->converter('markdown', $options);

		return $converter->convert($text);
	}

	/**
	 * @param string $target
	 * @param array<string, mixed> $options
	 *
	 * @return \MarkupCarve\Carve\CarveConverter
	 */
	protected function converter(string $target, array $options): CarveConverter {
		$key = md5(serialize($options));
		if (!isset($this->converters[$target]) || ($this->converterKeys[$target] ?? null) !== $key) {
			$profile = $this->resolveProfile($options['profile'] ?? null);
			if ($target === 'html') {
				$this->converters[$target] = new CarveConverter(
					xhtml: $options['xhtml'] ?? false,
					warnings: $options['warnings'] ?? false,
					strict: $options['strict'] ?? false,
					safeMode: $options['safeMode'] ?? true,
					profile: $profile,
				);
			} else {
				$parser = new BlockParser(
					collectWarnings: $options['warnings'] ?? false,
					strictMode: $options['strict'] ?? false,
				);
				$converter = $target === 'text'
					? CarveConverter::plainText($parser)
					: CarveConverter::markdown($parser);
				$this->converters[$target] = $converter->setProfile($profile);
			}
			$this->converterKeys[$target] = $key;
		}

		return $this->converters[$target];
	}

	/**
	 * Resolve a profile from configuration.
	 *
	 * @param \MarkupCarve\Carve\Profile|string|null $profile Profile instance, name, or null
	 * @return \MarkupCarve\Carve\Profile|null
	 */
	protected function resolveProfile(Profile|string|null $profile): ?Profile {
		if ($profile instanceof Profile) {
			return $profile;
		}

		if ($profile === null) {
			return null;
		}

		return match ($profile) {
			'full' => Profile::full(),
			'article' => Profile::article(),
			'comment' => Profile::comment(),
			'minimal' => Profile::minimal(),
			default => null,
		};
	}

}
