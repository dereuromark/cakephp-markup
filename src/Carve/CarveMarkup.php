<?php

namespace Markup\Carve;

use Cake\Core\InstanceConfigTrait;
use Carve\CarveConverter;
use Carve\Profile;

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
	 * Cached converter, keyed by the hash of options used to build it.
	 * Per-call options that differ from the cached key trigger a rebuild,
	 * preventing a `safeMode=false` instance from serving a later call that
	 * requested `safeMode=true` — a real risk in long-lived FPM/queue workers.
	 *
	 * @var \Carve\CarveConverter|null
	 */
	protected ?CarveConverter $converter = null;

	/**
	 * @var string|null
	 */
	protected ?string $converterKey = null;

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

		$converter = $this->converter($options);

		return $converter->convert($text);
	}

	/**
	 * @param array<string, mixed> $options
	 *
	 * @return \Carve\CarveConverter
	 */
	protected function converter(array $options): CarveConverter {
		$key = md5(serialize($options));
		if (!($this->converter instanceof CarveConverter) || $this->converterKey !== $key) {
			$profile = $this->resolveProfile($options['profile'] ?? null);

			$this->converter = new CarveConverter(
				xhtml: $options['xhtml'] ?? false,
				warnings: $options['warnings'] ?? false,
				strict: $options['strict'] ?? false,
				safeMode: $options['safeMode'] ?? true,
				profile: $profile,
			);
			$this->converterKey = $key;
		}

		return $this->converter;
	}

	/**
	 * Resolve a profile from configuration.
	 *
	 * @param \Carve\Profile|string|null $profile Profile instance, name, or null
	 * @return \Carve\Profile|null
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
