<?php

namespace Markup\Djot;

use Cake\Core\InstanceConfigTrait;
use Djot\DjotConverter;
use Djot\Profile;

/**
 * Djot markup converter using php-collective/djot.
 *
 * Djot is a modern markup language created by John MacFarlane (author of CommonMark/Pandoc).
 *
 * @link https://djot.net/ Djot specification
 * @link https://github.com/php-collective/djot-php PHP implementation
 */
class DjotMarkup implements DjotInterface {

	use InstanceConfigTrait;

	/**
	 * @var \Djot\DjotConverter|null
	 */
	protected ?DjotConverter $converter = null;

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
	 * @return \Djot\DjotConverter
	 */
	protected function converter(array $options): DjotConverter {
		// Create new converter if options differ from cached one
		if ($this->converter === null) {
			$profile = $this->resolveProfile($options['profile'] ?? null);

			$this->converter = new DjotConverter(
				xhtml: $options['xhtml'] ?? false,
				warnings: $options['warnings'] ?? false,
				strict: $options['strict'] ?? false,
				safeMode: $options['safeMode'] ?? true,
				profile: $profile,
			);
		}

		return $this->converter;
	}

	/**
	 * Resolve a profile from configuration.
	 *
	 * @param \Djot\Profile|string|null $profile Profile instance, name, or null
	 * @return \Djot\Profile|null
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
